<?php

namespace PhpSlides\Controller;

/**
 * Example Route controller class
 */
final class PostsController extends Controller
{
    public function __invoke()
    {
        return "Invoked Success";
    }

    public function Post(int $id)
    {
        return "Post Page " . $id;
    }
}