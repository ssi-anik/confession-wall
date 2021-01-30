<?php

namespace App\GraphQL\Mutations;

use App\GraphQL\Types\Error;
use App\GraphQL\Types\Success;
use App\Models\Confession;

class DeleteConfession
{
    public function __invoke ($_, array $args) {
        $confession = Confession::where('receiver_id', auth()->user()->id)->find($args['id']);

        if (!$confession) {
            return new Error([ 'message' => 'The confession either does not exist or belongs to you.', ]);
        }

        $confession->delete();

        return new Success([
            'message' => 'The confession is successfully deleted.',
            'info'    => [ 'id' => $args['id'] ],
        ]);
    }
}
