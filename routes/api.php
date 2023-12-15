<?php

use PhpSlides\Api;

Api::get("/api/posts");
Api::post("/api/posts/{id}", @Post);
