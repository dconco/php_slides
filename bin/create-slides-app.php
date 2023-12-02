<?php

$projectDir = $argv[1];

if (!is_dir($projectDir))
{
    mkdir($projectDir);
}

$templateDir = __DIR__ . '/templates';
$files = glob($templateDir . '/*');

foreach ($files as $file)
{
    $fileName = basename($file);
    $newFileName = str_replace('template', $projectDir, $fileName);
    copy($file, $newFileName);
}

echo "PhpSlides project created successfully: {$projectDir}\n";