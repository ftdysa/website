<?php

namespace Ftdysa\Website;

class Image extends \SplFileInfo {
    // This is the path relative to web/ so I can do:
    // <img src="$relative_path/$filename">
    // I may have multiple files with the same name, but in different directories
    //  /kohtao/diving.jpg
    //  /kohpipi/diving.jpg
    private $relative_path;

    public function __construct($file, $relative_path) {
        parent::__construct($file);

        $this->relative_path = $relative_path;
    }

    public static function createFromFile(\SplFileInfo $file, $relative_path) {
        return new self($file->getRealPath(), $relative_path);
    }

    /**
     * Return the path relative to web, useful for creating
     * <img> tags.
     *
     * @return mixed
     */
    public function getRelativePath() {
        return $this->relative_path;
    }

    /**
     * Return the web accessible path to the full sized image.
     *
     * @return string
     */
    public function getDisplayName() {
        return ImageHelper::IMAGE_PATH.$this->relative_path.'/'.$this->getFilename();
    }

    /**
     * Return name of thumbnail.
     *
     * Ex: subfolder/image-245x180.jpg
     *
     * @param int $w
     * @param int $h
     * @return string
     */
    public function getThumbnailName($w = 370, $h = 200) {
        return ImageHelper::createThumbnailName($this, $w, $h);
    }

    /**
     * Return the web accessible path to thumbnail.
     *
     * @param int $w
     * @param int $h
     * @return string
     */
    public function getThumbnail($w = 370, $h = 200) {
        return ImageHelper::THUMB_PATH.$this->getThumbnailName($w, $h);
    }
}
