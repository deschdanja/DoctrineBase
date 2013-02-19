<?php

namespace deschdanja\DoctrineBase\Entities;

use deschdanja\DoctrineBase\ManipulationDefinitionCollection;
use deschdanja\DoctrineBase\Exceptions\OperationNotAllowed;

/**
 * Class provides base functionality for doctrine 2 entities
 * 
 * @author Theodor
 */
class EntityBase implements IEntityBase {

    private $uuidGenerator = "deschdanja\\DoctrineBase\\UUIDGenerator";
    protected $uuidFieldName = "";
    protected $nullableDTOFields;
    protected $nonNullableDTOFields;
    
    public function __construct() {
        $this->nullableDTOFields = array();
        $this->nonNullableDTOFields = array();
    }

    /**
     * returns a StdClass object containing all non object (except DateTime)
     * public, protected, private variables of the entity
     * 
     * this object is NOT connected to the entity at all!
     * @return \StdClass 
     */
    public function getEntityData() {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if (is_object($value) && !$value instanceof \DateTime) {
                unset($vars[$key]);
            }
        }
        return (object) $vars;
    }

    /**
     * Function sets parameters defined in
     * $this->nonNullableDTOFields and
     * $this->nullableDTOFields
     * from the dto to this Entity
     * 
     * using set[Key] method if exists
     * otherwise value will be set directly whithout any validation!
     * 
     * @param \deschdanja\DoctrineBase\Entities\EntityDTO $DTO
     */
    public function setDataByDTO(EntityDTO $dto) {
        foreach($this->nonNullableDTOFields as $arg){
            $argname = strtolower($arg);
            if(!empty($dto->$argname)){
                $setFunction = "set".$arg;
                if(method_exists($this, $setFunction)){
                    $this->$setFunction($dto->$argname);
                }else{
                    $this->$argname = $dto->$argname;
                }
                
            }
        }
        
        foreach($this->nullableDTOFields as $arg){
            $argname = strtolower($arg);
            if($dto->$argname !== false){
                $setFunction = "set".$arg;
                if(method_exists($this, $setFunction)){
                    $this->$setFunction($dto->$argname);
                }else{
                    $this->$argname = $dto->$arg;
                }
            }
        }
    }

    /**
     * Function returns IManipulationDefinitionCollection
     * used to further modify Schema
     * 
     * @return IManipulationDefinitionCollection
     */
    public static function getManipulationDefinitions() {
        return new ManipulationDefinitionCollection();
    }

    public function setUUID() {
        $field = strval($this->uuidFieldName);
        if ($field != "") {
            if ($this->$field != "") {
                throw new OperationNotAllowed("UUID field must not be changed!");
            }
            $generator = $this->uuidGenerator;
            $this->$field = $generator::createUUID();
        }
    }

}

?>
