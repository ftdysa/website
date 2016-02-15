<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;

require __DIR__.'/../vendor/autoload.php';

$app = new Application();

$app['debug'] = true;

$app->register(new TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../src/Resources/views'
]);

$app->get('/', 'Ftdysa\Website\Controller\HomeController::processAction');

$app->get('/about', function() use ($app) {
    return $app['twig']->render('about.html.twig');
});

$app->get('/photos', 'Ftdysa\Website\Controller\PhotoController::processAction');

$app->run();