<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/** @var $loader \Composer\Autoload\ClassLoader */
$loader = require 'vendor/autoload.php';

if (!isset($loader)) {
    throw new RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

$loader->addPsr4('Dolphin\\PHPUnit\Tests\\Timings\\', __DIR__);
$loader->register();
unset($loader);
