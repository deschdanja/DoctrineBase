<?php
namespace deschdanja\DoctrineBase\Entities;
use deschdanja\DoctrineBase\Exceptions\BaseException;

/**
 * Data transfer Object for entities in deschdanja namespace
 *
 * @author Theodor
 */
class EntityDTO {
    /**
     * Adding new properties is not allowed in DTO
     * 
     * @param string $name
     * @param mixed $value
     * @throws BaseException
     */
    public function __set($name, $value){
        throw new BaseException("Adding a new property to a DTO is prohibited");
    }
}

?>
