<?php

namespace deschdanja\DoctrineBaseTest\Entities;

use deschdanja\DoctrineBase\Entities\ManagedTable;

/**
 * Description of UserGroup
 *
 * @author Theodor Stoll <theodor@deschdanja.ch>
 */
class UserGroup extends ManagedTable{
    protected $id;
    
    protected $groupname;
    
    protected $Users;
}

?>
