<?php

require_once "vendor/autoload.php";
use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();