<?php

namespace Database\Seeders;

use App\Models\Leader;
use App\Models\LeaderMember;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::create([
            'name' => 'Fikri',
            'email' => 'fikri@admin.com',
            'password' => Hash::make('Theblues!7'),
            'role' => 'admin'
        ]);

        Member::factory(10)->create();

        Leader::create([
            'member_id' => 1
        ]);

        Leader::create([
            'member_id' => 2
        ]);

        Leader::create([
            'member_id' => 3
        ]);

        Leader::create([
            'member_id' => 4
        ]);

        Leader::create([
            'member_id' => 5
        ]);

        LeaderMember::create([
            'leader_id' => 1,
            'member_id' => 6
        ]);

        LeaderMember::create([
            'leader_id' => 2,
            'member_id' => 7
        ]);

        LeaderMember::create([
            'leader_id' => 3,
            'member_id' => 8
        ]);

        LeaderMember::create([
            'leader_id' => 3,
            'member_id' => 9
        ]);
    }
}
