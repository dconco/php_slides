<?php

use PhpSlides\Controller\PostsController;

return [
  "/posts" => PostsController::class,
  "/posts/{id}" => PostsController::class,
];
