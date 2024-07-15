<?php

declare(strict_types=1);

namespace PhpSlides\Http;

interface ApiController
{
	// GET
	public function index(Request $request);

	// GET
	public function show(Request $request);

	// POST
	public function store(Request $request);

	// PUT
	public function update(Request $request);

	// PATCH
	public function patch(Request $request);

	// DELETE
	public function destroy(Request $request);
}
