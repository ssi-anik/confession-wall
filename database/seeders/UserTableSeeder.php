<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    public function run () {
        User::create([
            'name'     => 'Confession wall',
            'username' => 'confession-wall',
            'email'    => 'confession.wall@confession-wall.com',
            'password' => 12345,
        ]);
    }
}
