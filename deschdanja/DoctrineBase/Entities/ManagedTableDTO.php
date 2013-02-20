<?php
namespace deschdanja\DoctrineBase\Entities;
use deschdanja\DoctrineBase\Exceptions\BaseException;

/**
 * ManagedTableDTO is the baseclass for all DTOs
 * extending ManagedTable
 *
 * @author Theodor Stoll <theodor@deschdanja.ch>
 */
class ManagedTableDTO extends EntityDTO{
    
    public $lastmodifiedby;
    
    public $lastmodified;
    
    public $version;
}

?>
