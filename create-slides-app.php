<?php

// Load Composer autoloader
require 'vendor/autoload.php';

use Composer\Script\CommandEvent;

$event = new CommandEvent($GLOBALS['dispatcher'], 'create-slides-app', []);

// Get project name and target directory from arguments
$projectName = $event->getArgument(1);
$targetDir = $event->getArgument(2);

// Validate project name
if (!preg_match('/^[a-z0-9\-_]+$/', $projectName))
{
    echo "Invalid project name. Please use alphanumeric characters, underscores, and hyphens.\n";
    exit(1);
}

// Check if target directory exists
if (file_exists($targetDir))
{
    echo "Target directory already exists. Please choose a different directory.\n";
    exit(1);
}

// Download project ZIP file
$zipUrl = 'https://packagist.org/packages/dconco/php_slides/archive/master.zip';
$zipFile = tempnam(sys_get_temp_dir(), 'slides-app');
file_put_contents($zipFile, file_get_contents($zipUrl));

// Extract ZIP file to a temporary directory
$tempDir = sys_get_temp_dir() . '/slides-app-extract';
mkdir($tempDir);

$zip = new ZipArchive();
$zip->open($zipFile);
$zip->extractTo($tempDir);
$zip->close();

// Copy only the desired files and folders
$templateDir = $tempDir . '/master/templates';
$vendorDir = $tempDir . '/master/vendor';

if (!file_exists($targetDir . '/templates'))
{
    mkdir($targetDir . '/templates');
}

copyDir($templateDir, $targetDir . '/templates');

if (!file_exists($targetDir . '/vendor'))
{
    mkdir($targetDir . '/vendor');
}

copyDir($vendorDir, $targetDir . '/vendor');

// Remove temporary directories
rrmdir($tempDir);
unlink($zipFile);

// Set project directory permissions
chmod($targetDir . '/' . $projectName, 0755, true);

echo "Project files copied successfully!\n";