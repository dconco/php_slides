<?php

use PhpSlides\Api;

Api::get("/posts");
Api::post("/posts/{id}", @Post);
