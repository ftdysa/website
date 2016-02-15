<?php

namespace Ftdysa\Website\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class ImageMetadataCommand extends Command {
    protected function configure() {
        $this
            ->setName('metadata')
            ->setDescription('Read image metadata, maybe do things with it.')
            ->addOption('src', 's', InputOption::VALUE_REQUIRED, 'Path to images');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $src = $input->getOption('src');

        $finder = new Finder();
        $finder->files()->in($src);

        foreach ($finder as $img) {
            $filename = $img->getPath().'/'.$img->getBasename();
            var_dump($filename);
            $data = exif_read_data($filename, 'IFD0');

            echo var_export($data, true);
        }
    }
}