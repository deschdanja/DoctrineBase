<?php
namespace deschdanja\DoctrineBase;

use \Doctrine\ORM\EntityManager;
use deschdanja\DoctrineBase\Exceptions\ForeignKeyException;

/**
 * Description of ForeignKeyDefinition
 *
 * @author Theodor
 */
class ForeignKeyDefinition {
    
    /**
     * @var string 
     */
    protected $owningClassName;
    
    /**
     * @var string
     */
    protected $owningTableName;
    
    /**
     * @var array 
     */
    protected $owningFieldNames;
    
    /**
     * @var array 
     */
    protected $owningColumnNames = array();
    
    /**
     * @var string 
     */
    protected $foreignClassName;
    
    /**
     * @var string 
     */
    protected $foreignTableName;
    
    /**
     * @var array 
     */
    protected $foreignFieldNames;
    
    /**
     * @var array 
     */
    protected $foreignColumnNames = array();
    
    /**
     * @var ForeignKeyOptionsDTO 
     */
    protected $options;
    
    private $resolved = false;
    
    /**
     *
     * @param string $owningEntityClassname
     * @param array $owningFieldNames
     * @param string $foreignEntityClassname
     * @param array $foreignFieldNames
     * @param ForeignKeyOptionsDTO $options optional
     * @param EntityManager $em optional
     */
    function __construct($owningEntityClassname, array $owningFieldNames, $foreignEntityClassname, array $foreignFieldNames, ForeignKeyOptionsDTO $options = NULL, EntityManager $em = NULL){
        $this->owningClassName = trim(strval($owningEntityClassname));
        $this->owningFieldNames = $owningFieldNames;
        $this->foreignClassName = trim(strval($foreignEntityClassname));
        $this->foreignFieldNames = $foreignFieldNames;
        $this->resolved = false;
        
        if(is_null($options)){
            $this->options = new ForeignKeyOptionsDTO();
        }else{
            $this->options = $options;
        }
        
        if(!is_null($em)){
            $this->resolve($this->em);
        }
        
    }
    
    /**
     * Function resolves given ClassNames and Fieldnames
     * with provided EntityManager
     * TableNames and columnNames can only be retrieved if resolving was
     * successful
     * 
     * @param Doctrine\ORM\EntityManager $em
     * @return boolean 
     */
    public function resolve(EntityManager $em){
        $this->resolved = false;
        
        $owningMeta = $em->getClassMetadata($this->owningClassName);
        $foreignMeta = $em->getClassMetadata($this->foreignClassName);
        
        $this->owningTableName = $owningMeta->getTableName();
        $this->foreignTableName = $foreignMeta->getTableName();
        
        if(count($this->foreignFieldNames) == 0 || count($this->owningFieldNames) == 0){
            return $this->resolved;
        }
        
        foreach ($this->owningFieldNames as $fieldName) {
            $this->owningColumnNames[] = $owningMeta->getColumnName($fieldName);
        }
        foreach ($this->foreignFieldNames as $fieldName) {
            $this->foreignColumnNames[] = $foreignMeta->getColumnName($fieldName);
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
            throw new ForeignKeyException("ForeignKey Definitions could not be resolved. Therefore no data of this instance is usable");
        }
    }
    
    /**
     * @return string 
     */
    public function getOwningClassName(){
        return $this->owningClassName;
    }
    
    /**
     * @return array 
     */
    public function getOwningFieldNames(){
        return $this->owningFieldNames;
    }
    
    /**
     * Returns resolved Tablename
     * @return string
     * @throws Exception if resolving failed
     */
    public function getOwningTableName(){
        $this->isResolved();
        return $this->owningTableName;
    }
    
    /**
     * Returns resolved ColumnNames
     * @return array 
     * @throws Exception if resolving failed
     */
    public function getOwningColumnNames(){
        $this->isResolved();
        return $this->owningColumnNames;
    }
    
    /**
     *
     * @return string 
     */
    public function getForeignClassName(){
        return $this->foreignClassName;
    }
    /**
     * @return array 
     */
    public function getForeignFieldNames(){
        return $this->foreignFieldNames;
    }
    
    /**
     * Returns resolved Tablename
     * @return string
     * @throws Exception if resolving failed
     */
    public function getForeignTableName(){
        $this->isResolved();
        return $this->foreignTableName;
    }
    
    /**
     * Returns resolved ColumnNames
     * @return array 
     * @throws Exception if resolving failed
     */
    public function getForeignColumnNames(){
        $this->isResolved();
        return $this->foreignColumnNames;
    }
    
    /**
     *
     * @return ForeignKeyOptionsDT
     */
    public function getOptions(){
        return $this->options;
    }
    
    
}

?>
