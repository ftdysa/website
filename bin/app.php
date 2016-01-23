<?php

use Ftdysa\Website\Command\ResizeImagesCommand;
use Symfony\Component\Console\Application;

require __DIR__.'/../vendor/autoload.php';

$app = new Application();
$app->add(new ResizeImagesCommand());
$app->run();