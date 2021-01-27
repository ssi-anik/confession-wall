<?php

namespace App\GraphQL\Queries;

class Greet
{
    public function __invoke ($_, array $args) {
        return sprintf('Hey "%s". Hello there! I\'m greeting from graphql server. :D', $args['name']);
    }
}
