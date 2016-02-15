<?php

namespace Ftdysa\Website\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class GeneratePhotoCacheCommand extends Command {
    const OUTPUT_FILE = 'src/ImageCache.php';

    protected function configure() {
        $this
            ->setName('image:gen-cache')
            ->setDescription(
                'Generate a cache class so I don\'t have to read off disk to see what '.
                'images I currently have.'
            )
            ->addOption(
                'src',
                's',
                InputOption::VALUE_REQUIRED,
                'Path to images.',
                'src/Resources/images/'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $src = $input->getOption('src');

        if (!file_exists($src)) {
            $output->writeln(sprintf('%s does not exist', $src));
        }

        $finder = new Finder();
        $finder->files()->in($src);

        $images = [];
        foreach ($finder as $file) {
            $relative_path = str_replace($src, '', $file->getPath());
            $images[] = $this->makeImageTemplate($file->getRealPath(), $relative_path);
        }

        $fh = fopen(self::OUTPUT_FILE, 'w');
        fwrite($fh, $this->makeCache($images));
        fclose($fh);

        $output->writeln('<info>Cache created</info>');
    }

    private function makeCache(array $images) {
        $template = <<<EOS
<?php

namespace Ftdysa\Website;

class ImageCache {
    private static \$cache = [\n
EOS;

        foreach ($images as $photo_class_str) {
            $template .= $photo_class_str."\n";
        }

        $template .= <<<EOS
    ];

    public static function getCache() {
        return self::\$cache;
    }

    public static function getImages() {
        \$photos = [];
        foreach (self::\$cache as \$file => \$relative_path) {
            \$photos[] = new Image(\$file, \$relative_path);
        }

        return \$photos;
    }
}
EOS;
        return $template;
    }

    private function makeImageTemplate($file, $relative_path) {
        return sprintf("        '%s' => '%s',", $file, $relative_path);
    }
}
