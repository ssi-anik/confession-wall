<?php

namespace App\GraphQL\Queries;

use App\Models\Confession;

class SpecificConfession
{
    public function __invoke ($_, array $args) {
        return Confession::with('user')->find($args['id']);
    }
}
