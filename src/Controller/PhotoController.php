<?php

namespace Ftdysa\Website\Controller;

use Ftdysa\Website\ImageCache;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class PhotoController {

    public function processAction(Request $request, Application $app) {
        return $app['twig']->render('photos.html.twig', ['photos' => ImageCache::getImages()]);
    }
}