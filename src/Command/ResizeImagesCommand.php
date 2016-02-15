<?php

namespace Ftdysa\Website\Command;

use Ftdysa\Website\Image;
use Intervention\Image\Image as ImageManipulator;
use Intervention\Image\ImageManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ResizeImagesCommand extends Command {
    protected function configure() {
        $this
            ->setName('resize')
            ->setDescription(
                'Create thumbnails from available photos, skipping those that '.
                'already exist.'.
                'This will walk the --src directory recursively, creating a thumbnail '.
                'for each thumbnail found within in the --dst directory.'.
                'The filename of the thumbnail will be: original_name-hxw.ext (image-100x100.jpg)'
            )
            ->addOption(
                'src',
                's',
                InputOption::VALUE_REQUIRED,
                'Base path of images. This will walk all directories found within and '.
                'create a mirrored directory structure inside --thumb-path',
                'src/Resources/images/'
            )
            ->addOption(
                'dst',
                'd',
                InputOption::VALUE_REQUIRED,
                'Base path where thumbnails should be stored. This will created a mirrored '.
                'structure to --src',
                'src/Resources/thumbs/'
            )
            ->addOption(
                'width',
                'w',
                InputOption::VALUE_REQUIRED,
                'Width of thumbnail',
                200
            )
            ->addOption(
                'height',
                'hh',
                InputOption::VALUE_REQUIRED,
                'Height of thumbnail',
                200
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $src = $input->getOption('src');
        $dst = $input->getOption('dst');
        $h = $input->getOption('height');
        $w = $input->getOption('width');

        $errors = [];
        if (!file_exists($src)) {
            $errors[] = sprintf('Source directory: %s does not exist.', $src);
        }

        if (!file_exists($dst)) {
            $errors[] = sprintf('Destination directory: %s does not exist.', $dst);
        }

        if (!is_writable($dst)) {
            $errors[] = sprintf('Destination directory: %s is not writable.', $dst);
        }

        if ($errors) {
            array_unshift($errors, 'There was an error!');
            $error_msg = $this->getHelper('formatter')->formatBlock($errors, 'error');
            $output->writeln($error_msg);
        }

        $finder = new Finder();
        $finder->files()->in($src);

        $manager = new ImageManager(['driver' => 'gd']);
        $rows = [];

        foreach ($finder as $file) {
            $relative_path = $this->getRelativePath($src, $file);
            $thumb_name = Image::createFromFile($file, $relative_path)
                ->getThumbnailName($w, $h);
            $thumb_path = $dst.$thumb_name;

            if (file_exists($thumb_path)) {
                $rows[] = [
                    '<error>Skipped</error>',
                    $file->getRealpath(),
                    $thumb_path
                ];
                continue;
            }

            $image = $manager->make($file->getRealpath());
            $image->widen($w);

            $wrote = $this->writeThumb($thumb_path, $image);
            if (!$wrote) {
                $output->writeln(sprintf('<error>%s</error>', 'Could not write '.$thumb_path));
            }

            $rows[] = ['<info>Processed</info>', $file->getRealpath(), $thumb_path];
        }

        $table = new Table($output);
        $table->setHeaders(['Status', 'Source Img', 'Thumb Img']);
        $table->setRows($rows);
        $table->render();
    }

    /**
     * Write the re-sized image to the destination path,
     * creating any relative directories necessary.
     *
     * @param $dst_path
     * @param ImageManipulator $image
     * @return bool|Image
     */
    private function writeThumb($dst_path, ImageManipulator $image) {
        $parts = explode('/', $dst_path);
        array_pop($parts);
        $path = implode('/', $parts);

        if (!file_exists($path)) {
            if (!mkdir($path, 0775)) {
                return false;
            }
        }

        return $image->save($dst_path);
    }

    /**
     * Make the path relative to a given path.
     *
     * If path = src/Resources/images
     * Pathname = src/Resources/images/subdir/image.png
     * Relativepath = subdir/image.png
     *
     * @param $path
     * @param SplFileInfo $file
     * @return mixed
     */
    private function getRelativePath($path, SplFileInfo $file) {
        return str_replace(
            $path,
            '',
            $file->getPath()
        );
    }
}