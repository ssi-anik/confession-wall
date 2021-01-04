<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function boot () {
        app('validator')->extend('username', function ($attribute, $value) {
            return preg_match('/^[A-Za-z0-9\-]+$/', $value);
        }, 'The :attribute can only contain alpha (A-Z) and numeric (0-9) and dash (-)');
    }
}
