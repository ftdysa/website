<?php

use Ftdysa\Website\Command\GeneratePhotoCacheCommand;
use Ftdysa\Website\Command\ImageMetadataCommand;
use Ftdysa\Website\Command\ResizeImagesCommand;
use Symfony\Component\Console\Application;

require __DIR__.'/../vendor/autoload.php';

$app = new Application();
$app->add(new ResizeImagesCommand());
$app->add(new GeneratePhotoCacheCommand());
$app->add(new ImageMetadataCommand());
$app->run();