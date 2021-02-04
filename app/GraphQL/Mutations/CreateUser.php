<?php

namespace App\GraphQL\Mutations;

use Carbon\Carbon;
use Faker\Factory;

class CreateUser
{
    public function __invoke ($_, array $args) {
        $faker = Factory::create();
        $user = [
            'id'         => rand(),
            'name'       => $faker->name(),
            'email'      => $faker->email,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
            'has_arg'    => (bool) ($args['name'] ?? false),
        ];

        # \Nuwave\Lighthouse\Execution\Utils\Subscription::broadcast('userCreated', $user);

        return $user;
    }
}
