<?php

namespace Statwig\Statwig\Services;

use Statwig\Statwig\Exceptions\DirectoryNotReadableException;
use Statwig\Statwig\Helpers\FileFinder;
use Twig_Environment;

class TwigParserService
{
    const INPUT_EXTENSION = '.html.twig';
    const OUTPUT_EXTENSION = '.html';

    protected $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function execute($templates, $output)
    {
        if ( ! is_readable($output)) {
            throw new DirectoryNotReadableException($output);
        }

        $files = (new FileFinder())
            ->fromDirectoryWithExtension($templates, self::INPUT_EXTENSION);

        foreach ($files as $file) {
            $contents = $this->twig->render($file);

            $fileName = str_replace(self::INPUT_EXTENSION, self::OUTPUT_EXTENSION, $file);
            $filePath = rtrim($output, '/') . '/' . $fileName;

            file_put_contents($filePath, $contents);
        }
    }
}
