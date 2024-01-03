<?php

use PhpSlides\Api;

Api::get("/api/posts", POST_INVOKE);
Api::get("/api/posts/{id}", POST);