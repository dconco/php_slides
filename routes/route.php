<?php

use PhpSlides\view;
use PhpSlides\Route;

include dirname(__DIR__) . '/vendor/autoload.php';

/**
 *  ---------------------------------------------------------------------------------------------------------------------------
 *  |   This function must be presented at the beginning before any other codes for security reasons and in handling files request
 *  |   The parameter contains only be a boolean, which indicates request logger to prints out logs output on each received request 
 *  ---------------------------------------------------------------------------------------------------------------------------
 */
Route::config();


/**
 *  --------------------------------------------------------------------
 *  |   Register all routes here to render according to request
 *  |   NOTE - that browser or any other request cannot access any page 
 *  |   that are not coming from route, it redirects to 404
 *  --------------------------------------------------------------------
 */

Route::view('/dashboard', '::Dashboard');
Route::redirect('/', '/dashboard');

Route::any('*', view::render('::Errors::404'));