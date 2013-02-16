<?php
namespace deschdanja\DoctrineBase;

/**
 *
 * @author Theodor
 */
interface IManipulationDefinitionCollection {
    
    /**
     * returns an array containing ForeignKeyDefinition
     * @return array 
     */
    public function getFKsToAdd();
    
    /**
     * returns an array containing ForeignKeyDefinition
     * @return array 
     */
    public function getFKsToRemove();
    
    /**
     * returns an array containing IndexDefinition
     * @return array 
     */
    public function getIndexesToAdd();
    
    /**
     * returns an array containing IndexDefinition
     * @return array 
     */
    public function getIndexesToRemove();
    
    /**
     *@param ForeignKeyDefinition $fk 
     */
    public function addFKtoAdd(ForeignKeyDefinition $fk);
    
    /**
     *@param ForeignKeyDefinition $fk 
     */
    public function addFKtoRemove(ForeignKeyDefinition $fk);
    
    /**
     *@param IndexDefinition $index
     */
    public function addIndexToAdd(IndexDefinition $index);
    
    /**
     *@param IndexDefinition $index
     */
    public function addIndexToRemove(IndexDefinition $index);
}

?>
