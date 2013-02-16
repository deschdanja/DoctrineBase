<?php

namespace deschdanja\DoctrineBase;

/**
 * Description of ManipulationDefinitionCollection
 *
 * @author Theodor
 */
class ManipulationDefinitionCollection implements IManipulationDefinitionCollection {

    protected $fkToAdd = array();
    protected $fkToRemove = array();
    protected $indexToAdd = array();
    protected $indexToRemove = array();

    /**
     * returns an array containing ForeignKeyDefinition
     * @return array 
     */
    public function getFKsToAdd() {
        return $this->fkToAdd;
    }

    /**
     * returns an array containing ForeignKeyDefinition
     * @return array 
     */
    public function getFKsToRemove() {
        return $this->fkToRemove;
    }

    /**
     * returns an array containing IndexDefinition
     * @return array 
     */
    public function getIndexesToAdd() {
        return $this->indexToAdd;
    }

    /**
     * returns an array containing IndexDefinition
     * @return array 
     */
    public function getIndexesToRemove() {
        return $this->indexToRemove;
    }

    /**
     * @param ForeignKeyDefinition $fk 
     */
    public function addFKtoAdd(ForeignKeyDefinition $fk) {
        $this->fkToAdd [] = $fk;
    }

    /**
     * @param ForeignKeyDefinition $fk 
     */
    public function addFKtoRemove(ForeignKeyDefinition $fk){
        $this->fkToRemove[] = $fk;
    }

    /**
     * @param IndexDefinition $index
     */
    public function addIndexToAdd(IndexDefinition $index){
        $this->indexToAdd[] = $index;
    }
        

    /**
     * @param IndexDefinition $index
     */
    public function addIndexToRemove(IndexDefinition $index){
        $this->indexToRemove[] = $index;
    }
}

?>
