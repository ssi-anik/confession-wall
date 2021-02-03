<?php

namespace App\GraphQL\Queries;

use Faker\Factory;

class InternalArgument
{
    public function __invoke ($_, array $args) {
        $faker = Factory::create();

        $data = [
            'id'                => $faker->randomNumber(),
            'name'              => $args['name'],
        ];

        return $data;
    }
}
