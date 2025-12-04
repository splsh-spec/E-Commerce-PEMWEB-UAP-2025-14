<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $member1 = User::create([
            'name' => 'Member Satu',
            'email' => 'member1@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        $member2 = User::create([
            'name' => 'Member Dua',
            'email' => 'member2@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);
    }
}
