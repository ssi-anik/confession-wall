<?php

namespace App\GraphQL\Queries;

use App\GraphQL\Types\Success;
use App\Models\Confession;

class MyConfession
{
    public function __invoke ($_, array $args) {
        $user = auth()->user();
        $page = $args['page'] ?? 1;
        $page = $page < 1 ? 1 : $page;
        $results = Confession::with('poster')
                             ->where('receiver_id', $user->id)
                             ->latest()
                             ->paginate(10, [ '*' ], 'page', $page);

        return new Success([
            'items'      => $results->getCollection()->transform(function ($row) {
                return [
                    'id'           => $row->id,
                    'body'         => $row->body,
                    'is_public'    => $row->poster_id ? false : true,
                    'is_anonymous' => $row->poster_id && $row->is_anonymous,
                    'poster'       => !$row->is_anonymous && $row->poster ? $row->poster->username : null,
                    'posted_at'    => $row->created_at->toDateTimeString(),
                ];
            }),
            'pagination' => [
                'has_prev' => $results->previousPageUrl() ? true : false,
                'has_next' => $results->hasMorePages(),
            ],
        ]);
    }
}
