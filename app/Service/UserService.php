<?php

namespace App\Service;

class UserService
{
    public static function checkProfileCompletion($user)
    {
        if ($user->gender == null || $user->phone == null) {
            return false;
        }
        return true;
    }

}
