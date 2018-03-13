<?php

namespace Statwig\Statwig\Commands;

use Statwig\Statwig\Helpers\DirectoryParser;
use Statwig\Statwig\Services\TwigParserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

        $twigParser = new TwigParserService();
        $twigParser->execute($templatesDirectory, $outputDirectory);
    }
}
