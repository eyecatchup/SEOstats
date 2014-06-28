<?php

/* @var $loader \Composer\Autoload\ClassLoader */
$loader = require __DIR__.'/../vendor/autoload.php';

$classMap1 = \Composer\Autoload\ClassMapGenerator::createMap(__DIR__);
$loader->addClassMap($classMap1);

error_reporting(E_ALL);
