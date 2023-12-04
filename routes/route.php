<?php

use PhpSlides\view;
use PhpSlides\Route;
use PhpSlides\Controller\PostsController;

include dirname(__DIR__) . '/vendor/autoload.php';

/**
 *  ---------------------------------------------------------------------------------------------------------------------------
 *  |   This function must be presented at the beginning before any other codes for security reasons and in handling files request
 *  |   The parameter contains only be a boolean, which indicates request logger to prints out logs output on each received request 
 *  ---------------------------------------------------------------------------------------------------------------------------
 */
Route::config(true);


/**
 *  --------------------------------------------------------------------
 *  |   Register all routes here to render according to request
 *  |   NOTE - that browser or any other request cannot access any page 
 *  |   that are not coming from route, it redirects to 404
 *  --------------------------------------------------------------------
 */

Route::view([ '/', '/index' ], '::dashboard');

Route::get('/post', [ PostsController::class]);
Route::get('/post/{id}', [ PostsController::class, 'Post' ]);

Route::notFound(view::render('::errors::404'));