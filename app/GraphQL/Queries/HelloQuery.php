<?php

namespace App\GraphQL\Queries;

class HelloQuery
{
    public function __invoke ($_, array $args) {
        return 'This is custom resolver using __invoke method';
    }

    public function methodNameHere ($_, array $args) {
        return 'This method uses the FQCN and the method name';
    }
}
