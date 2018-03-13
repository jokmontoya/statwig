<?php

namespace Statwig\Statwig\Commands;

use Statwig\Statwig\Exceptions\DirectoryNotReadableException;
use Statwig\Statwig\Exceptions\DirectoryNotWritableException;
use Statwig\Statwig\Helpers\DirectoryParser;
use Statwig\Statwig\Services\TwigParserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ParseCommand extends Command
{
    protected function configure()
    {
        $this->setName('parse')
            ->setDescription('Parses configured files into html templates')
            ->setHelp('This command allows you to parse Twig templates into html views.')
            ->addArgument('templates', InputArgument::OPTIONAL, 'Templates directory', 'templates/')
            ->addArgument('output', InputArgument::OPTIONAL, 'Output directory', 'output/')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directoryParser = new DirectoryParser();

        $templatesDirectory = $directoryParser->parse(BASE_PATH, $input->getArgument('templates'));
        $outputDirectory = $directoryParser->parse(BASE_PATH, $input->getArgument('output'));

        $loader = new \Twig_Loader_Filesystem($templatesDirectory);
        $twig = new \Twig_Environment($loader);
        $finder = new Finder();
        $filesystem = new Filesystem();

        try {
            (new TwigParserService($twig, $filesystem, $finder))
                ->execute($templatesDirectory, $outputDirectory);
        } catch (DirectoryNotReadableException $e) {
            $output->writeln('<error>' . $e->getMessage() . ' directory does not exist or is not readable.</error>');
        } catch (DirectoryNotWritableException $e) {
            $output->writeln('<error>' . $e->getMessage() . ' directory does not exist or is not writable.</error>');
        }

        $output->writeln('<info>Views parsed successfully.</info>');
    }
}
