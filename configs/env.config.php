<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeImmutable(dirname(__DIR__));
$dotenv->load();