<?php

namespace Statwig\Statwig\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseCommand extends Command
{
    protected function configure()
    {
        $this->setName('parse')
            ->setDescription('Parses configured files into html templates')
            ->setHelp('This command allows you to parse Twig templates into html views.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Statwig 0.0.1</info>');
    }
}
