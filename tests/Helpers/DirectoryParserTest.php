<?php

namespace Tests\Helpers;

use Statwig\Statwig\Helpers\DirectoryParser;
use Tests\TestCase;

class DirectoryParserTest extends TestCase
{
    /**
     * @test
     * @dataProvider relativePathsDataProvider
     */
    function paths_are_relative_by_default($root, $path, $expected)
    {
        $parser = new DirectoryParser();

        $this->assertEquals($expected, $parser->parse($root, $path));
    }

    /**
     * @test
     * @dataProvider absolutePathsDataProvider
     */
    function absolute_paths_are_not_converted_to_relative($root, $path, $expected)
    {
        $parser = new DirectoryParser();

        $this->assertEquals($expected, $parser->parse($root, $path));
    }

    function relativePathsDataProvider()
    {
        return [
            [ __DIR__, 'templates', __DIR__.'/templates' ],
            [ __DIR__, 'cache/templates', __DIR__.'/cache/templates' ],
            [ '/var/www/cache/', 'templates', '/var/www/cache/templates' ],
            [ '/var/www/cache', 'templates', '/var/www/cache/templates' ],
        ];
    }

    function absolutePathsDataProvider()
    {
        return [
            [ '/var/www/cache/', '/templates', '/templates' ],
            [ '/home/Sites', '/var/cache/templates', '/var/cache/templates' ],
            [ '/home/Sites/', '/var/cache/templates', '/var/cache/templates' ],
        ];
    }
}
