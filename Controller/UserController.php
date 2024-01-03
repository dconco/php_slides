<?php

namespace PhpSlides\Controller;

/**
 * Example of Web Route controller class
 */
final class UserController extends Controller
{
  public function __invoke()
  {
    return "Invoked & get all users successful.";
  }

  public function User(string $user_name)
  {
    return "Username = $user_name";
  }
}