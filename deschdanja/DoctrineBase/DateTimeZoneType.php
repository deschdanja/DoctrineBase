<?php
namespace deschdanja\DoctrineBase;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

/**
 * Description of DateTimeZoneType
 *
 * @author Theodor Stoll <theodor@deschdanja.ch>
 */
class DateTimeZoneType extends StringType{
    public function getName(){
        return "DateTimeZone";
    }
    
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        
        return $value->getName();
    }
    
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $val = new \DateTimeZone($value);
        
        return $val;
    }
}

?>
