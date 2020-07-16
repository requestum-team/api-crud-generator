#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Requestum\ApiGeneratorBundle\Command\ApiGeneratorCommand;

$application = new Application();

$application->add(new ApiGeneratorCommand());

$application->run();
