<?php

declare(strict_types=1);

namespace PhpSlides\Http;

/**
 * Interface ApiController
 *
 * This interface defines the standard methods for handling HTTP requests
 * in a RESTful API. Implementations of this interface should provide
 * logic for handling various HTTP methods such as GET, POST, PUT, PATCH, and DELETE.
 */
interface ApiController
{
	/**
	 * Handles GET requests to retrieve a list of resources.
	 *
	 * @param Request $request The request object containing request parameters.
	 * @return mixed The response containing the list of resources.
	 */
	public function index(Request $request);


	/**
	 * Handles GET requests to retrieve a single resource by ID.
	 *
	 * @param Request $request The request object containing request parameters.
	 * @return mixed The response containing the resource data.
	 */
	public function show(Request $request);


	/**
	 * Handles POST requests to create a new resource.
	 *
	 * @param Request $request The request object containing the data for the new resource.
	 * @return mixed The response indicating the result of the creation operation.
	 */
	public function store(Request $request);


	/**
	 * Handles PUT requests to update an existing resource.
	 *
	 * @param Request $request The request object containing the updated data for the resource.
	 * @return mixed The response indicating the result of the update operation.
	 */
	public function update(Request $request);


	/**
	 * Handles PATCH requests to partially update an existing resource.
	 *
	 * @param Request $request The request object containing the partial data for the resource.
	 * @return mixed The response indicating the result of the partial update operation.
	 */
	public function patch(Request $request);


	/**
	 * Handles DELETE requests to delete a resource.
	 *
	 * @param Request $request The request object containing the identifier of the resource to be deleted.
	 * @return mixed The response indicating the result of the delete operation.
	 */
	public function destroy(Request $request);


	/**
	 * Handles requests that result in an error.
	 *
	 * @param Request $request The request object containing request parameters.
	 * @return mixed The response indicating the error.
	 */
	public function error(Request $request);


	/**
	 * Handles any requests that do not match other methods.
	 *
	 * @param Request $request The request object containing request parameters.
	 * @return mixed The response indicating the default action.
	 */
	public function __default(Request $request);
}
