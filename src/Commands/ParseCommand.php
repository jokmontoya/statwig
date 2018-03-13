<?php

namespace Statwig\Statwig\Commands;

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
        $output->writeln('<info>Statwig 0.0.1</info>');
        $output->writeln(BASE_PATH);
        $output->writeln(CACHE_PATH);
        $output->writeln($input->getArgument('templates'));
        $output->writeln($input->getArgument('output'));
    }
}
