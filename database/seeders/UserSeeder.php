<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
       
        $userId = '01994cf9-dd16-73e5-9463-27531d62c1ee';

       
        DB::table('users')->where('id', $userId)->delete();

        
        DB::table('users')->insert([
            'id' => $userId,
            'name' => 'Test User',
            'email' => 'test@example.com',
          
            'instagram_token' => 'MOCK_TOKEN',
            'instagram_token_expires_at' => Carbon::parse('2025-12-30'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}