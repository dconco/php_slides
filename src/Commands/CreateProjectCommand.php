<?php

namespace dconco\php_slides;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProjectCommand extends BaseCommand
{
    protected static $defaultName = 'create-slides-app';

    protected function configure()
    {
        $this
            ->setDescription('Creates a new PHP slides project')
            ->setDefinition([
                new InputArgument('project-name', InputArgument::REQUIRED, 'The name of the project'),
            ])
            ->setHelp('Create a new PHP slides project with the specified name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectName = $input->getArgument('project-name');

        // Create the project directory
        $projectDir = getcwd() . '/' . $projectName;
        mkdir($projectDir);

        // Copy the template files to the project directory
        $templateDir = __DIR__ . '/templates';
        $files = glob($templateDir . '/*');

        foreach ($files as $file)
        {
            $fileName = basename($file);
            $newFileName = str_replace('template', $projectName, $fileName);
            copy($file, $projectDir . '/' . $newFileName);
        }

        // Display a success message
        $output->writeln('<info>Project created successfully: ' . $projectDir . '</info>');
    }
}
