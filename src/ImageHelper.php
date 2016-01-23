<?php

namespace Ftdysa\Website;

class ImageHelper {
    const IMAGE_PATH = 'images/';
    const THUMB_PATH = 'thumbs/';
    const THUMB_SUFFIX = '-%sx%s';

    public static function getThumbnailSuffix($w, $h) {
        return sprintf(self::THUMB_SUFFIX, $w, $h);
    }

    /**
     * Create a thumbnail name from a reference image.
     *
     * Given an image with:
     *  name = test.jpg
     *  relativePath = some/path/
     *
     * Return:
     *  some/path/test-wxh.jpg
     *
     * @param Image $refImage
     * @param $w
     * @param $h
     * @return string
     */
    public static function createThumbnailName(Image $refImage, $w, $h) {
        $suffix = self::getThumbnailSuffix($w, $h);

        $name_without_ext = $refImage->getBasename('.'.$refImage->getExtension());
        $thumb_filename = $name_without_ext.$suffix.'.'.$refImage->getExtension();

        return $refImage->getRelativePath().'/'.$thumb_filename;
    }
}