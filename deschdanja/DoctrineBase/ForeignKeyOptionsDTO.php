<?php

namespace deschdanja\DoctrineBase;

/**
 * Description of ForeignKeyOptionsDTO
 *
 * @author Theodor
 */
class ForeignKeyOptionsDTO {
    /**
     * defines onUpdate Status of ForeignKey
     * 
     * @var string, default "RESTRICT"
     */
    public $onUpdate = "RESTRICT";
    
    /**
     * defines onDelete Status of ForeignKey
     * @var string, default "RESTRICT" 
     */
    public $onDelete = "RESTRICT";
    
}

?>
