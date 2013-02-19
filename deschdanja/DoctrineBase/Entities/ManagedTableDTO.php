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
    
    protected $lastmodifiedby;
    
    protected $lastmodified;
    
    protected $version;
}

?>
