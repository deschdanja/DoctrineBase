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
    protected $entityDTOClassname = 'deschdanja\DoctrineBase\Entities\EntityDTO';

    public function __construct() {
        $this->nullableDTOFields = array();
        $this->nonNullableDTOFields = array();
    }

    /**
     * returns DTO filled with entity data
     *
     * @return EntityDTO
     */
    public function getDTO() {
        $classname = $this->entityDTOClassname;
        $dto = new $classname();
        foreach ($dto as $var) {
            $getMethod = "get" . ucfirst($var);
            if(method_exists($this, $getMethod)){
                $dto->$var = $this->$getMethod();
            }elseif(property_exists($this, $var)){
                $dto->$var = $this->$var;
            }
        }
        return $dto;
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
        foreach ($this->nonNullableDTOFields as $arg) {
            $argname = strtolower($arg);
            if (!empty($dto->$argname)) {
                $setFunction = "set" . $arg;
                if (method_exists($this, $setFunction)) {
                    $this->$setFunction($dto->$argname);
                } else {
                    $this->$argname = $dto->$argname;
                }
            }
        }

        foreach ($this->nullableDTOFields as $arg) {
            $argname = strtolower($arg);
            if ($dto->$argname !== false) {
                $setFunction = "set" . $arg;
                if (method_exists($this, $setFunction)) {
                    $this->$setFunction($dto->$argname);
                } else {
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
