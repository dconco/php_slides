<?php

namespace PhpSlides\Controller;

final class PostsController extends Controller
{
    public function __invoke()
    {
        echo "Invoked Success";
    }

    public function Post(int $id)
    {
        return "Post Page " . $id;
    }
}