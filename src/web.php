<?php

use PhpSlides\Controller\PostsController;

return [
  "/api/posts" => PostsController::class,
  "/api/posts/{id}" => PostsController::class,
];
