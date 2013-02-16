<?php
namespace deschdanja\DoctrineBaseTest\Entities;

use deschdanja\DoctrineBase\Entities\ManagedTable;

/**
 * Description of Address
 *
 * @author Theodor Stoll <theodor@deschdanja.ch>
 */
class Address extends ManagedTable{
    protected $id;
    protected $street;
    protected $streetno;
    protected $PLZ;
    protected $town;
    protected $country;
}

?>
