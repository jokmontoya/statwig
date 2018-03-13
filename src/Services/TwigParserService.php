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
        $this->checkIfDirectoriesExist($templates, $output);

        $this->cleanOutputDirectory($output);

        $files = $this->findInputFiles($templates);

        foreach ($files as $file) {
            $contents = $this->twig->render($file->getFilename());

            $this->saveCompiledTemplate($output, $file, $contents);
        }
    }

    protected function checkIfDirectoriesExist($templates, $output)
    {
        if (!$this->filesystem->exists($templates)) {
            throw new \InvalidArgumentException($templates);
        }

        if (!$this->filesystem->exists($output)) {
            throw new \InvalidArgumentException($output);
        }
    }

    protected function cleanOutputDirectory($output)
    {
        $outputFiles = $this->finder->in($output)
            ->files()
            ->depth(0)
            ->name('*' . self::OUTPUT_EXTENSION);

        $this->filesystem->remove($outputFiles);
    }

    protected function findInputFiles($templates)
    {
        return $this->finder->in($templates)
            ->files()
            ->depth(0)
            ->name('*' . self::INPUT_EXTENSION);
    }

    protected function saveCompiledTemplate($output, $file, $contents)
    {
        $fileName = str_replace(self::INPUT_EXTENSION, self::OUTPUT_EXTENSION, $file->getFilename());
        $filePath = rtrim($output, '/') . '/' . $fileName;

        $this->filesystem->remove($filePath);
        $this->filesystem->appendToFile($filePath, $contents);
    }
}
