<?php

namespace Ftdysa\Website\Controller;

use Ftdysa\Website\Image;
use Ftdysa\Website\ImageCache;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class PhotoController {

    public function processAction(Request $request, Application $app) {
        $image_cache = ImageCache::getCache();

        $photos = [];
        foreach ($image_cache as $file => $relative_path) {
            $photos[] = new Image($file, $relative_path);
        }

        return $app['twig']->render('photos.html.twig', ['photos' => $photos]);
    }
}