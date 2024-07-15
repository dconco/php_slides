<?php

use PhpSlides\Route;

require_once dirname(__DIR__) . '/vendor/autoload.php';

require_once dirname(__DIR__) . '/configs/cors.php';
require_once dirname(__DIR__) . '/configs/env.config.php';
require_once dirname(__DIR__) . '/app/Functions.php';

/**
 * ---------------------------------------------------------------------------------------------------------------------------
 * | This function must be presented at the beginning before any other codes for security reasons and in handling files request
 * | The parameter contains only be a boolean, which indicates request logger to prints out logs output on each received request
 * ---------------------------------------------------------------------------------------------------------------------------
 */
Route::config();

require_once dirname(__DIR__) . '/routes/api.php';
require_once dirname(__DIR__) . '/routes/web.php';
