<?php
namespace deschdanja\DoctrineBaseTest\Entities;

use deschdanja\DoctrineBase\Entities\EntityBase;

/**
 * Description of UserCredentials
 *
 * @author Theodor Stoll <theodor@deschdanja.ch>
 */
class UserCredentials extends EntityBase{
    protected $id;
    
    protected $passwordhash;
}

?>
