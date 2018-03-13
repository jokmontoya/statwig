<?php

namespace Tests\Helpers;

use Statwig\Statwig\Exceptions\DirectoryNotReadableException;
use Statwig\Statwig\Exceptions\DirectoryNotWritableException;
use Statwig\Statwig\Services\TwigParserService;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Tests\TestCase;
use Twig_Environment;

class TwigParserServiceTest extends TestCase
{
    /** @var TwigParserService */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->twig = $twig = $this->mock(Twig_Environment::class);
        $this->finder = $finder = $this->mock(Finder::class);
        $this->filesystem = $filesystem = $this->mock(Filesystem::class);

        $this->service = new TwigParserService($twig, $filesystem, $finder);
    }


    /** @test */
    function templates_directory_has_to_be_readable()
    {
        $templatesDirectory = '/non-existent-directory';
        $outputDirectory = '/tmp';

        $this->filesystem->expects($this->once())
            ->method('exists')
            ->with($templatesDirectory)
            ->willReturn(false);

        $this->expectException(\InvalidArgumentException::class);
        $this->service->execute($templatesDirectory, $outputDirectory);
    }

    /** @test */
    function output_directory_has_to_be_writable()
    {
        $templatesDirectory = __DIR__.'/../files/';
        $outputDirectory = '/non-existent-directory';

        $this->filesystem->expects($this->exactly(2))
            ->method('exists')
            ->withConsecutive([ $this->equalTo($templatesDirectory) ], [ $this->equalTo($outputDirectory) ])
            ->willReturnOnConsecutiveCalls([ true, false ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->service->execute($templatesDirectory, $outputDirectory);
    }

    /** @test */
    function files_are_rendered_into_output()
    {
        $fs = new \VirtualFileSystem\FileSystem();
        $fs->createDirectory('/tmp');
        $fs->createDirectory('/tmp/output');
        $fs->createDirectory('/tmp/templates');
        $fs->createDirectory('/tmp/templates/layouts');

        $templatesDirectory = $fs->path('/tmp/templates');
        $outputDirectory =  $fs->path('/tmp/output');

        $testFile = <<<FILE
{% extends 'layouts/base.html.twig' %}
{% block body %}Test contents{% endblock %}
FILE;

        $layoutFile = '<div>{% block body %}{% endblock %}</div>';

        $fs->createFile('/tmp/templates/file.html.twig', 'File Contents');
        $fs->createFile('/tmp/templates/test.html.twig', $testFile);
        $fs->createFile('/tmp/templates/layouts/base.html.twig', $layoutFile);

        $twig = new Twig_Environment(new \Twig_Loader_Filesystem($templatesDirectory));
        $filesystem = new Filesystem();
        $finder = new Finder();

        $service = new TwigParserService($twig, $filesystem, $finder);
        $service->execute($templatesDirectory, $outputDirectory);

        $this->assertFileExists($fs->path('/tmp/output/file.html'));
        $this->assertFileExists($fs->path('/tmp/output/test.html'));
        $this->assertEquals('File Contents', file_get_contents($fs->path('/tmp/output/file.html')));
        $this->assertEquals('<div>Test contents</div>', file_get_contents($fs->path('/tmp/output/test.html')));
    }
}
