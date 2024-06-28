<?php

use PhpSlides\Controller\RouteController;

/**
 *    -----------------------------------------------------------
 *   |
 *   @param mixed $filename The file which to gets the contents
 *   @return mixed The executed included file received
 *   |
 *    -----------------------------------------------------------
 */
function slides_include($filename)
{
   $output = RouteController::slides_include($filename);
   return $output;
}

define("APP_DEBUG", getenv("APP_DEBUG", "true"));

const GET = "GET";
const PUT = "PUT";
const POST = "POST";
const PATCH = "PATCH";
const DELETE = "DELETE";
