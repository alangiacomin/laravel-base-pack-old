<?php

namespace Alangiacomin\LaravelBasePack\Repositories;

use Illuminate\Support\Facades\Auth;

class UserRepository extends Repository implements IUserRepository
{
    public function userPermissions(): array
    {
        $user = Auth::user();
        return isset($user) ? call_user_func([$user, 'getAllPermissions']) : [];
    }

    public function userDirectPermissions(): array
    {
        $user = Auth::user();
        return isset($user) ? call_user_func([$user, 'permissions'])->get() : [];
    }
}
