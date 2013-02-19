<?php

namespace deschdanja\DoctrineBase\Entities;

/**
 *
 * @author Theodor
 */
interface IEntityBase {
        
    /**
     * returns DTO filled with entity data
     * @return EntityDTO;
     */
    public function getDTO();
    
    /**
     * Function sets all public parameter in DTO to this Entity
     * 
     * if entity has a setKey method, this will be used
     * else the value will be set whithout any validation!
     * 
     * @param \deschdanja\DoctrineBase\Entities\EntityDTO $dto
     */
    public function setDataByDTO(EntityDTO $dto);
    
    /**
     * Function returns IManipulationDefinitionCollection
     * used to further modify Schema
     * 
     * @return IManipulationDefinitionCollection
     */
    public static function getManipulationDefinitions();
}

?>
