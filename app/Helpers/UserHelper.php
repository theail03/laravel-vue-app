<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class UserHelper
{
    
    /**
     * Check if the current user's ID matches the given user ID.
     * Return true if they match, false otherwise.
     *
     * @param int $userId
     * @return bool
     */
    public static function verifyUser($userId)
    {
        $user = Auth::user();
        return $user && $user->id == $userId;
    }

    /**
     * Perform user verification and abort if unauthorized.
     *
     * @param int $userId
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public static function authorizeUser($userId)
    {
        if (!self::verifyUser($userId)) {
            return abort(403, 'Unauthorized action.');
        }

        return null;
    }
}