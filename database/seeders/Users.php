<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user')->insert([
            'name' => "Admin",
            'surname' => "Admin",
            'email' => 'Admin@email.com',
            'password' => bcrypt('password'),
            'role' => 'Admin',
        ]);

        DB::table('user')->insert([
            'name' => "Owner",
            'surname' => "Owner",
            'email' => 'Owner@email.com',
            'password' => bcrypt('password'),
            'role' => 'Owner',
        ]);
    }
}
