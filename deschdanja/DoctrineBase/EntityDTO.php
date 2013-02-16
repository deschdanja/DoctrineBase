<?php
namespace deschdanja\DoctrineBase;
use deschdanja\DoctrineBase\Exceptions\BaseException;

/**
 * Description of EntityDTO
 *
 * @author Theodor
 */
class EntityDTO {
    public function __set($name, $value){
        throw new BaseException("Adding a new property to a DTO is prohibited");
    }
}

?>
