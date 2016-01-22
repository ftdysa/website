<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;

require __DIR__.'/../vendor/autoload.php';

$app = new Application();

$app['debug'] = true;

$app->register(new TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../src/Resources/views'
]);

$app->get('/', function() use ($app) {
    return $app['twig']->render('home.html.twig');
});

$app->get('/about', function() use ($app) {
    return $app['twig']->render('about.html.twig');
});

$app->get('/photos', function() use ($app) {
    return $app['twig']->render('photos.html.twig');
});

$app->run();