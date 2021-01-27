<?php

namespace App\GraphQL\Queries;

class GreetWithAge
{
    public function __invoke ($_, array $args) {
        if (!isset($args['age'])) {
            return null;
        }

        return sprintf('Your name is %s and your age is %d', $args['name'], $args['age']);
    }
}
