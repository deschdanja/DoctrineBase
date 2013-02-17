<?php

namespace deschdanja\DoctrineBase;

use \Doctrine\ORM\EntityManager;
use \Doctrine\ORM\Tools\SchemaTool;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;

/**
 * Description of SchemaBuilder
 *
 * @author Theodor
 */
class SchemaBuilder {

    /**
     * Function rebuilds a schema based on all classes in all paths
     * that were registered before in the $em;
     * Filename have to be classnames
     * Schema will be dropped and completely rebuilt!!
     * 
     * @param Doctrine\ORM\EntityManager $em
     * @param array $paths
     * @return void
     */
    public static function rebuildSchema(EntityManager $em, array $paths) {
        $metas = self::getMultiPathMetaDatas($em, $paths);
        $SchemaTool = new SchemaTool($em);
        $SchemaTool->dropSchema($metas);
        $SchemaTool->createSchema($metas);

        $fksToAdd = array();
        $fksToRemove = array();
        $indexesToAdd = array();
        $indexesToRemove = array();

        foreach ($metas as $meta) {
            $classname = '\\' . $meta->getName();
            $reflection = new \ReflectionClass($classname);
            if ($reflection->implementsInterface('\deschdanja\DoctrineBase\Entities\IEntityBase')) {
                $defs = $classname::getManipulationDefinitions();
                $fksToAdd = array_merge($fksToAdd, $defs->getFKsToAdd());
                $fksToRemove = array_merge($fksToRemove, $defs->getFKsToRemove());
                $indexesToAdd = array_merge($indexesToAdd, $defs->getIndexesToAdd());
                $indexesToRemove = array_merge($indexesToRemove, $defs->getIndexesToRemove());
            }
        }

        $statements = array();
        
        $origSchema = $em->getConnection()->getSchemaManager()->createSchema();
        $newSchema = clone $origSchema;
        
        foreach ($indexesToAdd as $index) {
            $index->resolve($em);
            self::addIndexToSchema($newSchema, $index);
        }

        foreach ($indexesToRemove as $index) {
            $index->resolve($em);
            self::removeIndexFromSchema($newSchema, $index);
        }

        $comparator = new Comparator();
        $schemaDiff = $comparator->compare($origSchema, $newSchema);

        $queries = $schemaDiff->toSaveSql($em->getConnection()->getDatabasePlatform());
        asort($queries); //make sure 'create index' is before 'drop index'
        $statements = array_merge($statements, $queries);

        $origSchema = $newSchema;
        $newSchema = clone $origSchema;
        
        foreach($fksToAdd as $fk){
            $fk->resolve($em);
            self::addForeignKeyToSchema($newSchema, $fk);
        }
        
        $comparator = new Comparator();
        $schemaDiff = $comparator->compare($origSchema, $newSchema);

        $queries = $schemaDiff->toSaveSql($em->getConnection()->getDatabasePlatform());
        arsort($queries); //make sure 'create index' is before 'alter table'
        $statements = array_merge($statements, $queries);
        
        $origSchema = $newSchema;
        $newSchema = clone $origSchema;
        
        foreach($fksToRemove as $fk){
            $fk->resolve($em);
            self::removeForeignKeyFromSchema($newSchema, $fk);
        }
        
        $comparator = new Comparator();
        $schemaDiff = $comparator->compare($origSchema, $newSchema);

        $queries = $schemaDiff->toSaveSql($em->getConnection()->getDatabasePlatform());
        asort($queries); //make sure 'alter table' is before 'drop index'
        
        $statements = array_merge($statements, $queries);
        
        foreach ($statements as $statement) {
            $em->getConnection()->exec($statement);
        }
    }

    private static function addForeignKeyToSchema(Schema $schema, ForeignKeyDefinition $fk) {
        $owningTable = $schema->getTable($fk->getOwningTableName());
        $foreignTable = $schema->getTable($fk->getForeignTableName());

        $indexes = $foreignTable->getIndexes();
        $indexfit = false;
        foreach ($indexes as $index) {
            if (count($fk->getForeignColumnNames()) == count($index->getColumns())) {
                $colfit = true;
                foreach ($fk->getForeignColumnNames() as $col) {
                    if (!in_array($col, $index->getColumns())) {
                        $colfit = false;
                        break;
                    }
                }
                if ($colfit) {
                    $indexfit = true;
                    break;
                }
            }
            if ($indexfit) {
                break;
            }
        }
        if ($indexfit === false) {
            $foreignTable->addUniqueIndex($fk->getForeignColumnNames());
        }

        $indexes = $owningTable->getIndexes();
        $indexfit = false;
        foreach ($indexes as $index) {
            if (count($fk->getOwningColumnNames()) == count($index->getColumns())) {
                $colfit = true;
                foreach ($fk->getOwningColumnNames() as $col) {
                    if (!in_array($col, $index->getColumns())) {
                        $colfit = false;
                        break;
                    }
                }
                if ($colfit) {
                    $indexfit = true;
                    break;
                }
            }
            if ($indexfit) {
                break;
            }
        }
        if ($indexfit === false) {
            $owningTable->addIndex($fk->getOwningColumnNames());
        }

        $owningTable->addForeignKeyConstraint($foreignTable, $fk->getOwningColumnNames(), $fk->getForeignColumnNames(), get_object_vars($fk->getOptions()));
    }

    private static function removeForeignKeyFromSchema(Schema $schema, ForeignKeyDefinition $fk) {
        $owningTable = $schema->getTable($fk->getOwningTableName());
        $foreignTable = $schema->getTable($fk->getForeignTableName());

        //REMOVE FK
        $foreignKeys = $owningTable->getForeignKeys();
        foreach ($foreignKeys as $oldFK) {
            if ($oldFK->getForeignTableName() == $fk->getForeignTableName() &&
                    $oldFK->getColumns() == $fk->getOwningColumnNames() &&
                    $oldFK->getForeignColumns() == $fk->getForeignColumnNames()) {
                $owningTable->removeForeignKey($oldFK->getName());
            }
        }

        //Remove Index with same cols as fk from foreign Table
        $indexes = $foreignTable->getIndexes();
        foreach ($indexes as $index) {
            $colfit = true;
            if (count($fk->getForeignColumnNames()) == count($index->getColumns())) {
                foreach ($fk->getForeignColumnNames() as $col) {
                    if (!in_array($col, $index->getColumns())) {
                        $colfit = false;
                        break;
                    }
                }
            } else {
                $colfit = false;
            }

            if ($colfit) {
                if (!$index->isPrimary()) {
                    $foreignTable->dropIndex($index->getName());
                    break;
                }
            }
        }

        //Remove Index with same cols as fk from owning Table
        $indexes = $owningTable->getIndexes();
        foreach ($indexes as $index) {
            $colfit = true;
            if (count($fk->getOwningColumnNames()) == count($index->getColumns())) {
                foreach ($fk->getOwningColumnNames() as $col) {
                    if (!in_array($col, $index->getColumns())) {
                        $colfit = false;
                        break;
                    }
                }
            } else {
                $colfit = false;
            }

            if ($colfit) {
                if (!$index->isPrimary()) {
                    $owningTable->dropIndex($index->getName());
                    break;
                }
            }
        }
    }

    private static function addIndexToSchema(Schema $schema, IndexDefinition $index) {
        $table = $schema->getTable($index->getTableName());
        $newIndex = new \Doctrine\DBAL\Schema\Index("testindex", $index->getColumnNames(), $index->getUnique());
        $indexes = $table->getIndexes();
        $exists = false;

        foreach ($indexes as $oldindex) {
            if ($newIndex->isFullfilledBy($oldindex)) {
                $exists = true;
                break;
            }
        }

        if ($exists === false) {
            if ($index->getUnique()) {
                $table->addUniqueIndex($index->getColumnNames());
            } else {
                $table->addIndex($index->getColumnNames());
            }
        }
    }

    private static function removeIndexFromSchema(Schema $schema, IndexDefinition $index) {
        $table = $schema->getTable($index->getTableName());
        $newIndex = new \Doctrine\DBAL\Schema\Index("testindex", $index->getColumnNames(), $index->getUnique());
        $indexes = $table->getIndexes();

        foreach ($indexes as $oldindex) {
            if ($newIndex->isFullfilledBy($oldindex) && $oldindex->isPrimary() === false) {
                $table->dropIndex($oldindex->getName());
            }
        }
    }

    /**
     * Returns array with all ClassMetadata for all classes in all paths
     * that were registered as entities before in $em
     * 
     * @param Doctrine\ORM\EntityManager $em
     * @param array $paths
     * @param boolean $includeSubdirs, default false
     * @return array containing Doctrine\ORM\Mapping\ClassMetadata
     */
    public static function getMultiPathMetaDatas(EntityManager $em, array $paths, $includeSubdirs = false) {
        $metas = array();

        foreach ($paths as $path) {
            if (is_string($path)) {
                //var_dump($path);
                $pathmetas = self::getMetaDatas($em, $path, $includeSubdirs);
                //var_dump(count($pathmetas));
                $metas = array_merge($metas, $pathmetas);
                //var_dump(count($metas));
            }
        }

        //var_dump(count($metas));

        return array_unique($metas);
    }

    /**
     * Returns array with all ClassMetadata for all classes in path
     * that were registered as entities before in $em
     * 
     * @param Doctrine\ORM\EntityManager $em
     * @param string $path
     * @param boolean $includeSubdirs, default false
     * @return array containing Doctrine\ORM\Mapping\ClassMetadata
     */
    private static function getMetaDatas(EntityManager $em, $path, $includeSubdirs = false) {
        $metas = array();

        //try to open directory
        if (is_dir($path)) {
            $dirhandle = opendir($path);
        } else {
            $dirhandle = false;
        }

        //if openinc successful
        if ($dirhandle !== false) {
            //iterate through files
            while (false !== ($file = readdir($dirhandle))) {
                //if php file
                if (!is_dir($path . DIRECTORY_SEPARATOR . $file) && strstr($file, ".php")) {
                    //open stream in read mode
                    $filehandle = fopen($path . DIRECTORY_SEPARATOR . $file, 'rb');
                    //if opening succesfull
                    if ($filehandle !== false) {
                        $namespace = false;
                        //iterate through lines and search for namespace
                        while (!feof($filehandle) && !$namespace) {
                            $line = fgets($filehandle);
                            if (stripos($line, 'namespace') !== false) {
                                $namespace = str_replace(";", "", str_replace("namespace", "", $line));
                            }
                        }

                        fclose($filehandle);

                        $namespace = trim(strval($namespace));
                        if ($namespace !== "") {
                            $namespace = $namespace . "\\";
                        }

                        list($className) = explode('.', $file);
                        try {

                            $meta = $em->getClassMetadata($namespace . $className);
                            $metas[] = $meta;
                        } catch (\Exception $exc) {
                            
                        }
                    }
                }
            }
        }

        if ($includeSubdirs === true) {
            //not implemented yet
            throw new Exception("feature include subdirs not implemented yet");
            /*
            $subdirs = FileSystemInfo::getSubdirectoriesPath($path);
            foreach ($subdirs as $subdir) {
                $submetas = self::getMetaDatas($em, $subdir, false);
                $metas = array_merge($metas, $submetas);
            }
             * 
             */
        }

        return $metas;
    }

    public static function expandPaths($paths) {
        //not implemented yet
            throw new Exception("feature expandPaths not implemented yet");
        /*
         * $subdirectories = array();

        foreach ($paths as $path) {
            $subdirectories = array_merge($subdirectories, FileSystemInfo::getSubdirectoriesPath($path));
        }
        $paths = array_merge($paths, $subdirectories);
        $paths = array_unique($paths);

        return $paths;
         */
        
    }

}

?>
