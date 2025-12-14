<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('canView')) {
    function canView(string $permission): bool
    {
        $user = Auth::user();
        if (! $user) return false;

        // Admin يرى كل شيء
        if ($user->hasRole('admin')) return true;

        return $user->can($permission);
    }
}
