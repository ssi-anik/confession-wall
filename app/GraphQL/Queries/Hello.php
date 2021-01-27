<?php

namespace App\GraphQL\Queries;

class Hello
{
    public function __invoke ($_, array $args) {
        return 'this is simple hello class without query appended on it';
    }
}
