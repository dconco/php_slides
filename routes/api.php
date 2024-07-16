<?php

use PhpSlides\Http\Api;
use PhpSlides\Controller\ApiExampleController;

/*
Api::v1()
	->route('/example/user/{id}', ApiExampleController::class)
	->middleware(['example'])->name('example.user');*/

Api::v1()
 ->define('/example/user', ApiExampleController::class)
 ->map([
  '/' => [ GET, '@index' ],
  '/{id}' => [ GET, '@show' ],
  '/delete/{id}' => [ DELETE, '@destroy' ]
 ])->name('example.user');
