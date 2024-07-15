<?php

declare(strict_types=1);

namespace PhpSlides\Controller;

use PhpSlides\Http\Request;
use PhpSlides\Http\ApiController;

final class ApiExampleController implements ApiController
{
	public function index (Request $request)
	{
		return 'Index Method';
	}

	public function show (Request $request)
	{
		$user_id = $request->urlParam()->id;
		$route = route('example.user', [ $user_id ]);

		return "Show Method.\nUser ID = $user_id\nRoute = $route";
	}

	public function store (Request $request)
	{
	}

	public function update (Request $request)
	{
	}

	public function patch (Request $request)
	{
	}

	public function destroy (Request $request)
	{
	}
}
