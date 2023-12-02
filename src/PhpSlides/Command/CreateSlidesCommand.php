<?php

namespace PhpSlides\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSlidesCommand extends Command
{
    protected static $defaultName = 'create-slides-app';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Logic for creating the slides app based on your template

        $output->writeln('Slides app created successfully.');

        return Command::SUCCESS;
    }
}