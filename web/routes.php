<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use PhpSlides\view;
use PhpSlides\Route;

/**
 * This function must be presented at the beginning before any other codes for security reasons and in handling files request
 * The parameter should only be a boolean, either true or false, which decides to prints out log output in each time a request has been gotten
 */
Route::config();


/* REGISTER ROUTES */

// view route
Route::view([ '/', '/index' ], 'views::index');

// // get route
Route::post('/profile/{user_id}', function (int $user_id)
{
    return json_encode([ 'user_id' => $user_id ]);
});

// // Handle not found errors
Route::notFound(function ()
{
    return view::render('views::errors::404');
});