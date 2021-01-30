<?php

namespace App\GraphQL\Queries;

use App\Models\Confession;

class ConfessionList
{
    public function __invoke ($_, array $args) {
        return Confession::/*with('receiver')->*/get();
    }
}
