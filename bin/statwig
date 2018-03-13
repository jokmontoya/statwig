#!/usr/bin/env php
<?php

define('BASE_PATH', realpath(__DIR__.'/../'));

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Statwig\Statwig\Commands\ParseCommand;

$app = new Application();
$app->add(new ParseCommand());
$app->setDefaultCommand('parse', true);

$app->run();
