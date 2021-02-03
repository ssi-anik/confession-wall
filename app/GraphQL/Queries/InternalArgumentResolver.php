<?php

namespace App\GraphQL\Queries;

class InternalArgumentResolver
{
    public function __invoke ($_, array $args) {
        return $args['text'] ?? 'Random Text is returned';
    }
}
