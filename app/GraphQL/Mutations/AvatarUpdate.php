<?php

namespace App\GraphQL\Mutations;

use App\GraphQL\Types\Success;
use Illuminate\Http\UploadedFile;

class AvatarUpdate
{
    public function __invoke ($_, array $args) {
        $profilePicture = null;
        if (isset($args['profile_picture']) && $args['profile_picture'] instanceof UploadedFile) {
            $profilePicture = $args['profile_picture']->store('avatars');
        }

        $user = auth()->user();
        $user->profile_picture = $profilePicture;
        $user->save();

        return new Success([
            'message' => sprintf('Successfully %s your profile picture.', $profilePicture ? 'saved' : 'removed'),
        ]);
    }
}
