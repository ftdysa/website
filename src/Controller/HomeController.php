<?php

namespace Ftdysa\Website\Controller;

use Ftdysa\Website\ImageCache;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class HomeController {
    public function processAction(Request $request, Application $app) {
        return $app['twig']->render('home.html.twig', ['photos' => ImageCache::getImages()]);
    }
}