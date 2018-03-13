<?php

namespace Statwig\Statwig\Services;

use Statwig\Statwig\Helpers\FileFinder;
use Twig_Environment;

class TwigParserService
{
    protected $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function execute($templates, $output)
    {
        $files = (new FileFinder())->fromDirectoryWithExtension($templates, '.html.twig');

        foreach ($files as $file) {
            echo $this->twig->render($file);
            exit;
        }
    }
}
