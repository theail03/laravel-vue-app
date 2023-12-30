<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class UserHelper
{

    /**
     * Check if the given user ID is different from the logged-in user's ID.
     * If different, return a 403 error response; otherwise, return null.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $userId
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public static function verifyUser($userId)
    {
        $user = Auth::user();

        if ($user && $user->id !== $userId) {
            return abort(403, 'Unauthorized action.');
        }

        return null;
    }
}