<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;

class GraphQLServiceProvider extends ServiceProvider
{
    public function boot (TypeRegistry $typeRegistry) {
        /*$typeRegistry->register(new \GraphQL\Type\Definition\ObjectType([
            'name'         => 'LoginPayload',
            'fields'       => [
                'access_token' => [ 'type' => \GraphQL\Type\Definition\Type::nonNull(\GraphQL\Type\Definition\Type::string()) ],
                'type'         => [ 'type' => \GraphQL\Type\Definition\Type::nonNull(\GraphQL\Type\Definition\Type::string()) ],
                'expires_in'   => [ 'type' => \GraphQL\Type\Definition\Type::nonNull(\GraphQL\Type\Definition\Type::int()) ],
            ],
            'resolveField' => function ($value, $args, $context, $info) {
                return $value->props[$info->fieldName];
            },
        ]));*/
    }
}