<?php

namespace Statwig\Statwig\Helpers;

use Statwig\Statwig\Exceptions\DirectoryNotReadableException;

class FileFinder
{
    /**
     * Iterates through the given $directory
     * And finds files with $extension
     */
    public function fromDirectoryWithExtension($directory, $extension): array
    {
        if ( ! is_readable($directory)) {
            throw new DirectoryNotReadableException($directory);
        }

        if ( 0 !== strpos($extension, '.' )) {
            $extension = '.' . $extension;
        }

        $extension = str_replace('.', '\.', $extension);

        $files = [];

        $iterator = new \DirectoryIterator($directory);

        foreach ($iterator as $fileinfo) {
            if ( ! $fileinfo->isFile()) continue;

            if ( ! preg_match('/^.*'.$extension.'$/', $fileinfo->getFilename())) {
                continue;
            }

            $files[] = $fileinfo->getFilename();
        }

        sort($files);

        return $files;
    }
}
