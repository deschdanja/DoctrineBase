<?php
namespace deschdanja\DoctrineBaseTest;

//standard autoloading
require_once '../../index.php';

//test autoloading
$loader = new \Composer\Autoload\ClassLoader();
$vendorDir = dirname(__DIR__);
$baseDir = dirname($vendorDir);
$loader->add('deschdanja\\DoctrineBaseTest', $baseDir . '/');
$loader->register();

//UTCDateTime
\deschdanja\DoctrineBase\UTCDateTimeType::addType("UTCDateTime",'deschdanja\DoctrineBase\UTCDateTimeType');

//prepare event manager
$evm = new \Doctrine\Common\EventManager;
$tablePrefix = new \deschdanja\DoctrineBase\TableNaming();
$evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

//config
$paths = array(__DIR__."/Entities", __DIR__."/../DoctrineBase/Entities");
$isDevMode = true;
$config = \Doctrine\ORM\Tools\Setup::createXMLMetadataConfiguration($paths, $isDevMode);

// the connection configuration

$dbParams = array(
    'driver' => 'pdo_mysql',
    'user' => 'tanzenneu_test',
    'password' => 'Triskel1',
    'host' => 'localhost',
    'dbname' => 'deschdanja_test',
    'charset' => 'utf8'
);

$em = \Doctrine\ORM\EntityManager::create($dbParams, $config, $evm);
\deschdanja\DoctrineBase\SchemaBuilder::rebuildSchema($em, $paths);
/*
$metas = \deschdanja\DoctrineBase\SchemaBuilder::getMultiPathMetaDatas($em, $paths);
var_dump($metas);
$schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
$schemaTool->createSchema($metas);
*/





?>
