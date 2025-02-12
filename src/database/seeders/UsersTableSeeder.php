<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'test_user1',
                'email' => 'test1@example.com',
                'password' => Hash::make('password123'),
                'postcode' => '123-4567',
                'address' => '東京都新宿区新宿1-1-1',
                'building' => 'テストマンション101',
                'image_path' => null
            ],
            [
                'name' => 'test_user2',
                'email' => 'test2@example.com',
                'password' => Hash::make('password123'),
                'postcode' => '987-6543',
                'address' => '大阪府大阪市梅田1-1-2',
                'building' => null,
                'image_path' => null
            ]
            ]);
    }
}
