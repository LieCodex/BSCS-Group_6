<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SquealtronSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'squealtron@local.ai'],
            [
                'name' => 'Squealtron',
                'password' => Hash::make(Str::random(32)),
                'is_ai' => true,
                'avatar' => 'assets/img/squealtronnew.png',
            ]
        );
    }
}

