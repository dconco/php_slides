<?php

include dirname(__DIR__) . '/vendor/autoload.php';

use PhpSlides\view;
use PhpSlides\Route;

/**
 * This function must be presented at the beginning before any other codes for security reasons and in handling files request
 * The parameter should only be a boolean, either true or false, which decides to prints out log output in each time a request has been gotten
 */
Route::config();


/**
 * Register all routes here to render according to request
 * `NOTE` that browser cannot access any page that are not coming from route, it redirects to 404
 */

Route::view([ '/', '/index' ], 'views::index');

Route::notFound(function ()
{
    return view::render('views::errors::404');
});