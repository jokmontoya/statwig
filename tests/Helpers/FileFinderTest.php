<?php

namespace Tests\Helpers;

use Statwig\Statwig\Exceptions\DirectoryNotReadableException;
use Statwig\Statwig\Helpers\FileFinder;
use Tests\TestCase;

class FileFinderTest extends TestCase
{
    /** @test */
    function directory_has_to_exist()
    {
        $this->expectException(DirectoryNotReadableException::class);

        $finder = new FileFinder();
        $finder->fromDirectoryWithExtension('/not/existing/directory', '.html.twig');
    }

    /** @test */
    function it_finds_files_with_given_extension()
    {
        $directory = __DIR__.'/../files';

        $finder = new FileFinder();
        $files = $finder->fromDirectoryWithExtension($directory, '.html.twig');

        $this->assertTrue(is_array($files));
        $this->assertCount(2, $files);
        $this->assertEquals([
            'file.html.twig',
            'test.html.twig'
        ], $files);
    }

    /** @test */
    function it_does_not_find_files_inside_folders()
    {
        $directory = __DIR__.'/../files';

        $finder = new FileFinder();
        $files = $finder->fromDirectoryWithExtension($directory, '.html.twig');

        $this->assertFalse(in_array('inside.html.twig', $files));
        $this->assertTrue(in_array('file.html.twig', $files));
        $this->assertTrue(in_array('test.html.twig', $files));
    }

    /** @test */
    function it_does_not_other_extensions()
    {
        $directory = __DIR__.'/../files';

        $finder = new FileFinder();
        $files = $finder->fromDirectoryWithExtension($directory, '.txt');

        $this->assertFalse(in_array('file.html.twig', $files));
        $this->assertFalse(in_array('test.html.twig', $files));
        $this->assertFalse(in_array('inside.html.twig', $files));
        $this->assertTrue(in_array('test.txt', $files));
    }
}
