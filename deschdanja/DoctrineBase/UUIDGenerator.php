<?php

namespace deschdanja\DoctrineBase;

/**
 * Description of UUIDGenerator
 *
 * @author Theodor Stoll <theodor@deschdanja.ch>
 */
class UUIDGenerator {
    public static function createUUID() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff));
    }
}
?>
