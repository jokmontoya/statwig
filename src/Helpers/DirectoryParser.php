<?php

namespace Statwig\Statwig\Helpers;

class DirectoryParser
{
    public function parse($root, $directory)
    {
        if (0 === strpos($directory, '/')) {
            return $directory;
        }

        return rtrim($root, '/') . '/' . trim($directory, '/');
    }
}
