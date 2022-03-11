<?php

namespace Xguard\Coordinator\database\seeds;

use Illuminate\Database\Seeder;
use Xguard\Coordinator\Models\Coordinator;

class CoordinatorSeeder extends Seeder
{
    public function run()
    {
        Coordinator::create([
            'user_id' => 1,
            'role' => 'admin',
        ]);
        Coordinator::create([
            'user_id' => 2,
            'role' => 'admin',
        ]);
        Coordinator::create([
            'user_id' => 3,
            'role' => 'admin',
        ]);
    }
}
