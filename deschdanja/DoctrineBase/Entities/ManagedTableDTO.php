<?php
namespace deschdanja\DoctrineBase\Entities;
use deschdanja\DoctrineBase\Exceptions\BaseException;

/**
 * Description of ManagedTableDTO
 *
 * @author Theodor Stoll <theodor@deschdanja.ch>
 */
class ManagedTableDTO extends EntityDTO{
    
    protected $lastmodifiedby;
    
    protected $lastmodified;
    
    protected $version;
}

?>
