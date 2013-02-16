<?php

namespace deschdanja\DoctrineBase\Entities;

/**
 *
 * @author Theodor
 */
interface IEntityBase {
    /**
     * returns a StdClass object containing all non object (except DateTime)
     * public, protected, private variables of the entity
     * 
     * this object is NOT connected to the entity at all!
     * @return \StdClass 
     */
    public function getEntityData();
    
    /**
     * Function sets all public parameter in DTO to this Entity
     * 
     * if entity has a setKey method, this will be used
     * else the value will be set whithout any validation!
     * 
     * @param EntityDTO $DTO
     */
    public function setData(EntityDTO $DTO);
    
    /**
     * Function returns IManipulationDefinitionCollection
     * used to further modify Schema
     * 
     * @return IManipulationDefinitionCollection
     */
    public static function getManipulationDefinitions();
}

?>
