<?php

namespace deschdanja\DoctrineBase\Entities;

/**
 * Class provides base functionality for doctrine 2 entities
 * 
 * @author Theodor
 */
class EntityBase implements IEntityBase{
    
    /**
     * returns a StdClass object containing all non object (except DateTime)
     * public, protected, private variables of the entity
     * 
     * this object is NOT connected to the entity at all!
     * @return \StdClass 
     */
    public function getEntityData(){
        $vars = get_object_vars($this);
        foreach($vars as $key => $value){
            if(is_object($value) && !$value instanceof \DateTime){
                unset($vars[$key]);
            }
        }
        return (object) $vars;
    }
    
    /**
     * Function sets all public parameter in DTO to this Entity
     * 
     * if entity has a setKey method, this will be used
     * else the value will be set whithout any validation!
     * 
     * @param EntityDTO $DTO
     */
    public function setData(EntityDTO $DTO){
        foreach($DTO as $key => $value){
            if(is_string($value)){
                $value = trim($value);
            }
            $method = "set".ucfirst($key);
            if(method_exists($this, $method)){
                $this->$method($value);
            }else{
                $this->$key = $value;
            }
        }
    }
    
    /**
     * Function returns IManipulationDefinitionCollection
     * used to further modify Schema
     * 
     * @return IManipulationDefinitionCollection
     */
    public static function getManipulationDefinitions(){
        return new ManipulationDefinitionCollection();
    }
}

?>