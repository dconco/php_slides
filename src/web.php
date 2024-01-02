<?php

use PhpSlides\Controller\PostsController;

echo 'Hello';

return [
  "post_invoke" => [ PostsController::class, '__invoke' ],
  "post" => [ PostsController::class, 'Post' ],
];