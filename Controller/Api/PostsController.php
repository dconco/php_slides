<?php

namespace PhpSlides\Controller;

/**
 * Example Api Route controller class
 */
final class PostsController extends Controller
{
  public function __invoke()
  {
    $resolve = [
      "status" => 200,
      "data" => "Invoked Successful.",
    ];

    return json_encode($resolve);
  }

  public function Post(int $id)
  {
    $resolve = [
      "status" => 200,
      "post_id" => $id,
      "data" => "Post Page = $id",
    ];

    return json_encode($resolve);
  }
}
