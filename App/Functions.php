<?php

use PhpSlides\Controller\RouteController;

/**
 *  -----------------------------------------------------------
 *  |
 *  @param mixed $filename The file which to gets the contents
 *  @return mixed The executed included file received
 *  |
 *  -----------------------------------------------------------
 */
function slides_include($filename)
{
  $output = RouteController::slides_include($filename);
  return $output;
}
