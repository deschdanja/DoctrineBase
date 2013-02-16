<?php

namespace deschdanja\DoctrineBase\Entities;

/**
 * ManagedTable is a mapped superclass
 * defines basic fields
 */
class ManagedTable extends EntityBase{
    
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
    
    public function setLastmodifiedBy($username){
        $username = trim(strval($username));
        if($username == ""){
            $username = "UNKNOWN";
        }
        $this->lastmodifiedby = $username;
    }
}

?>
