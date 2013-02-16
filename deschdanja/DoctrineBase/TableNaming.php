<?php

namespace deschdanja\DoctrineBase;

use \Doctrine\ORM\Event\LoadClassMetadataEventArgs;

/**
 * TableNaming is responsible to rename all tables whithin deschdanja namespace
 * this includes ManyToMany join tables
 * 
 * A listener of this type must be set up before the EntityManager has been initialised,
 * otherwise an Entity might be created or cached before the naming has been set.
 * 
 * use like this
 * $evm = new \Doctrine\Common\EventManager;
 * $tablePrefix = new \deschdanja\DoctrineBase\TableNaming();
 * $evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);
 * $em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config, $evm);
 *
 * @author Theodor Stoll <theodor@deschdanja.ch>
 */
class TableNaming {

    protected $prefix = '';

    public function __construct($prefix = "deschdanja") {
        $prefix = (string) $prefix;
        $this->prefix = trim($prefix);
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs) {
        $classMetadata = $eventArgs->getClassMetadata();

        $className = $classMetadata->getName();
        $namespaceArray = explode("\\", $className);

        if ($namespaceArray[0] == "deschdanja") {
            $classMetadata->setTableName(strtolower($this->prefix) . "_" . strtolower($namespaceArray[count($namespaceArray) - 1]));
            foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
                if ($mapping['type'] == \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY) {
                    if (array_key_exists('joinTable', $classMetadata->associationMappings[$fieldName])) {
                        $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
                        $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . "_" . $mappedTableName;
                    }
                }
            }
        }
    }

}

?>
