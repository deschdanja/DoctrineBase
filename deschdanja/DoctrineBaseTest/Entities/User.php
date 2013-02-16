<?php
namespace deschdanja\DoctrineBaseTest\Entities;

use deschdanja\DoctrineBase\Entities\EntityBase;


/**
 * Description of User
 *
 * @author Theodor Stoll <theodor@deschdanja.ch>
 */
class User extends EntityBase{
    /**
     * IDUser in db
     * @var integer
     */
    protected $IDUser;
    
    
    /**
     * varchar 30
     * @var string
     */
    protected $username;
    
    /**
     *
     * @var string
     */
    protected $firstname;
    
    /**
     *
     * @var string
     */
    protected $lastname;
    
    /**
     *
     * @var type 
     */
    protected $Credentials;
    
    /**
     *
     * @var type 
     */
    protected $Addresses;
    
    protected $Groups;
}

?>
