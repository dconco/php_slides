<?php

use PhpSlides\Http\Api;
use PhpSlides\Controller\ApiExampleController;

Api::v1()
	->route('/example/user/{id}', ApiExampleController::class)
	->middleware(['example'])
	->name('example.user');