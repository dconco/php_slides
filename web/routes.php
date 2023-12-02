<?php

$base_dir = dirname(__DIR__);
require_once $base_dir . '/vendor/autoload.php';

use PhpSlides\view;
use PhpSlides\Route;

/**
 * This function must be presented at the beginning before any other codes for security reasons and in handling files request
 * The parameter should only be a boolean, either true or false, which decides to prints out log output in each time a request has been gotten
 */
Route::config();


// Register API's
// Route::any("/api/v1/account/login", $api_dir);
// Route::any("/api/v1/account/register", $api_dir);
// Route::any("/api/v1/account/logout", $api_dir);
// Route::any("/api/v1/profile/{user_id}", $api_dir);
// Route::any("/api/v1/dashboard", $api_dir);

// // Register views page
// Route::any('/login', 'views/login.php');
// Route::any('/signup', 'views/signup.php');
// Route::any('/profile/{user_id}', 'views/profile.php');


/* REGISTER ROUTES */

// view route
Route::view([ '/', '/index' ], 'views::index');

// get route
Route::post('/profile/{user_id}', function (int $user_id)
{
    return json_encode([ 'user_id' => $user_id ]);
});

// Handle not found errors
Route::notFound(function ()
{
    return view::render('views::errors::404');
});