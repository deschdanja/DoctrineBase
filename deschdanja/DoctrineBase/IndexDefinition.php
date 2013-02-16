<?php
namespace deschdanja\DoctrineBase;

use \Doctrine\ORM\EntityManager;

/**
 * Description of IndexDefinition
 *
 * @author Theodor
 */
class IndexDefinition {
    /**
     * @var string 
     */
    protected $entityClassname;
    
    /**
     * @var string
     */
    protected $tableName;
    
    /**
     * @var array 
     */
    protected $fieldNames;
    
    /**
     * @var array 
     */
    protected $columnNames = array();
    
    /**
     * @var boolean 
     */
    protected $unique = false;
    
    protected $resolved = false;
    
    public function __construct($classname, array $fieldnames, $unique = false, EntityManager $em = NULL){
        $this->entityClassname = trim(strval($classname));
        $this->fieldNames = $fieldnames;
        if($unique === true){
            $this->unique = true;
        }
        $this->resolved = false;
        
        if(!is_null($em)){
            $this->resolve($em);
        }
    }
    
    public function resolve(EntityManager $em){
        $this->resolved = false;
        
        $meta = $em->getClassMetadata($this->entityClassname);
        $this->tableName = $meta->getTableName();
        
        if(count($this->fieldNames) == 0){
            return $this->resolved;
        }
        
        foreach($this->fieldNames as $field){
            $this->columnNames[] = $meta->getColumnName($field);
        }
        
        $this->resolved = true;
        return $this->resolved;
    }
    
    /**
     * Checks whether resolving was successful
     * throws exception if not!
     * @return boolean
     * @throws \Exception if instance not resolved
     */
    public function isResolved(){
        if($this->resolved){
            return true;
        }else{
            throw new \Exception("Index Definitions could not be resolved. Therefore no data of this instance is usable");
        }
    }
    
    /**
     *
     * @return string
     */
    public function getClassName(){
        return $this->entityClassname;
    }
    
    /**
     *
     * @return string
     * @throws \Exception if instance not resolved 
     */
    public function getTableName(){
        $this->isResolved();
        return $this->tableName;
    }
    
    /**
     *
     * @return string
     */
    public function getFieldNames(){
        return $this->fieldNames;
    }
    
    /**
     *
     * @return string
     * @throws \Exception if instance not resolved 
     */
    public function getColumnNames(){
        $this->isResolved();
        return $this->columnNames;
    }
    
    /**
     *
     * @return boolean
     */
    public function getUnique(){
        return $this->unique;
    }
    
}

?>
