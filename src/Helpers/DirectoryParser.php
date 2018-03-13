<?php

namespace Statwig\Statwig\Helpers;

class DirectoryParser
{
    /**
     * Checks if the given $directory has / at the beginning
     * And return absolute path or relative if no slash is found
     */
    public function parse($root, $directory)
    {
        if (0 === strpos($directory, '/')) {
            return $directory;
        }

        return rtrim($root, '/') . '/' . trim($directory, '/');
    }
}
