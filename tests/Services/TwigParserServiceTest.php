<?php

namespace Tests\Helpers;

use Statwig\Statwig\Exceptions\DirectoryNotReadableException;
use Statwig\Statwig\Exceptions\DirectoryNotWritableException;
use Statwig\Statwig\Services\TwigParserService;
use Tests\TestCase;

class TwigParserServiceTest extends TestCase
{
    /** @test */
    function templates_directory_has_to_be_readable()
    {
        $twig = $this->mock(\Twig_Environment::class);
        $service = new TwigParserService($twig);

        $templatesDirectory = '/non-existent-directory';
        $outputDirectory = '/tmp';

        $this->expectException(DirectoryNotReadableException::class);
        $service->execute($templatesDirectory, $outputDirectory);
    }

    /** @test */
    function output_directory_has_to_be_writable()
    {
        $twig = $this->mock(\Twig_Environment::class);
        $service = new TwigParserService($twig);

        $templatesDirectory = __DIR__.'/../files/';
        $outputDirectory = '/non-existent-directory';

        $this->expectException(DirectoryNotWritableException::class);
        $service->execute($templatesDirectory, $outputDirectory);
    }

    /** @test */
    function files_are_rendered_into_output()
    {
        $templatesDirectory = __DIR__.'/../files/';
        $outputDirectory =  __DIR__.'/../output/';

        $loader = new \Twig_Loader_Filesystem($templatesDirectory);
        $twig = new \Twig_Environment($loader);

        $service = new TwigParserService($twig);

        $service->execute($templatesDirectory, $outputDirectory);

        $this->assertFileExists($outputDirectory . 'file.html');
        $this->assertFileExists($outputDirectory . 'test.html');
        $this->assertEquals('File contents', file_get_contents($outputDirectory . 'file.html'));
        $this->assertEquals('<div>Test contents</div>', file_get_contents($outputDirectory . 'test.html'));
    }

    protected function tearDown()
    {
        $files = [ 'file', 'test' ];

        foreach ($files as $file) {
            $path = __DIR__.'/../output/'.$file.'.html';

            if ( ! is_readable($path)) continue;

            unlink($path);
        }
    }
}
