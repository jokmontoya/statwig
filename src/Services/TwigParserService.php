<?php

namespace Statwig\Statwig\Services;

use Statwig\Statwig\Exceptions\DirectoryNotReadableException;
use Statwig\Statwig\Exceptions\DirectoryNotWritableException;
use Statwig\Statwig\Helpers\FileFinder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Twig_Environment;

class TwigParserService
{
    const INPUT_EXTENSION = '.html.twig';
    const OUTPUT_EXTENSION = '.html';

    protected $twig;

    protected $filesystem;

    protected $finder;

    public function __construct(
        Twig_Environment $twig,
        Filesystem $filesystem,
        Finder $finder
    ) {
        $this->twig = $twig;
        $this->filesystem = $filesystem;
        $this->finder = $finder;
    }

    public function execute($templates, $output)
    {
        if ( ! $this->filesystem->exists($templates)) {
            throw new \InvalidArgumentException($templates);
        }

        if ( ! $this->filesystem->exists($output)) {
            throw new \InvalidArgumentException($output);
        }

        /** @var \SplFileInfo[] $files */
        $files = $this->finder->in($templates)
            ->files()
            ->depth(0)
            ->name('*' . self::INPUT_EXTENSION);

        foreach ($files as $file) {
            $contents = $this->twig->render($file->getFilename());

            $fileName = str_replace(self::INPUT_EXTENSION, self::OUTPUT_EXTENSION, $file->getFilename());
            $filePath = rtrim($output, '/') . '/' . $fileName;

            $this->filesystem->remove($filePath);
            $this->filesystem->appendToFile($filePath, $contents);
        }
    }
}
