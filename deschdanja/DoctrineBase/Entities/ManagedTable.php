<?php

namespace deschdanja\DoctrineBase\Entities;

/**
 * ManagedTable is a mapped superclass
 * defines basic fields
 */
class ManagedTable extends EntityBase{
    protected $entityDTOClassname = 'deschdanja\DoctrineBase\Entities\ManagedTableDTO';
            
    /**
     * @var string
     */
    protected $lastmodifiedby = "UNKNOWN";
    
    /**
     * @var DateTime 
     */
    protected $lastmodified;
    
    /**
     * @var integer 
     */
    protected $version;
    
    
    public function setLastmodified(){
        $this->lastmodified = new \DateTime("now", new \DateTimeZone("UTC"));
    }
    
    public function getLastmodifiedby(){
        return $this->lastmodifiedby;
    }
    
    public function getLastmodified(){
        return $this->lastmodified;
    }
    
    public function getVersion(){
        return $this->version;
    }
    
    public function setLastmodifiedBy($IDUser){
        $IDUser = trim(strval($IDUser));
        if($IDUser == ""){
            $IDUser = "UNKNOWN";
        }
        $this->lastmodifiedby = $IDUser;
    }
    
    protected function setDTOImportFields() {
        parent::setDTOImportFields();
        $nonnullable = array("Lastmodified", "Lastmodifiedby", "Version");
        $this->nonNullableDTOFields = array_merge($this->nonNullableDTOFields, $nonnullable);
    }
}

?>
