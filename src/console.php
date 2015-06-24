<?php

use Phalcon\DI\FactoryDefault\CLI as CliDI;
use Phalcon\CLI\Console as ConsoleApp;
use Phalcon\Config;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\CLI\Dispatcher;

include(__DIR__ . '/../../../autoload.php');

$di = new CliDI();

$di['phadConfig'] = function () {

    $config = require(__DIR__ . '/../../../../phad-config.php');
    return new Config($config);
};

$di['db'] = function () use ($di) {

    return new Mysql($di['config']->database->toArray());
};

$di['dispatcher'] = function () {

    $dispatcher = new Dispatcher();

    $dispatcher->setDefaultNamespace('Argentum88\Phad\Tasks');

    return $dispatcher;
};

$console = new ConsoleApp();
$console->setDI($di);

$arguments = array();
$params = array();

foreach($argv as $k => $arg) {
    if($k == 1) {
        $arguments['task'] = $arg;
    } elseif($k == 2) {
        $arguments['action'] = $arg;
    } elseif($k >= 3) {
        $params[] = $arg;
    }
}
if(count($params) > 0) {
    $arguments['params'] = $params;
}

define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

try {

    $console->handle($arguments);
}
catch (\Phalcon\Exception $e) {

    echo $e->getMessage();
    exit(255);
}