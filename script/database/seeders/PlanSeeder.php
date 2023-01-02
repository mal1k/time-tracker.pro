<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Term;
use App\Models\Termmeta;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create([
            'name' => 'Basic',
            'duration' => 15,
            'price' => 5,
            'storage_size' => 100,           
            'gps' => 1,
            'user_limit' => 10,
            'project_limit' => 5,
            'group_limit' => 10,
            'screenshot' => 1,
            'is_featured' => 0,
            'status' => 1,
        ]);

        Plan::create([
            'name' => 'Standard',
            'duration' => 15,
            'price' => 9.9,
            'storage_size' => 200,           
            'gps' => 1,
            'user_limit' => 15,
            'project_limit' => 10,
            'group_limit' => 15,
            'screenshot' => 1,
            'is_featured' => 0,
            'status' => 1,
        ]);

        Plan::create([
            'name' => 'Pro',
            'duration' => 15,
            'price' => 15.5,
            'storage_size' => 300,           
            'gps' => 1,
            'user_limit' => 20,
            'project_limit' => 15,
            'group_limit' => 20,
            'screenshot' => 1,
            'is_featured' => 1,
            'status' => 1,
        ]);

        Plan::create([
            'name' => 'Enterprise',
            'duration' => 15,
            'price' => 19.9,
            'storage_size' => 500,           
            'gps' => 1,
            'user_limit' => 30,
            'project_limit' => 30,
            'group_limit' => 30,
            'screenshot' => 1,
            'is_featured' => 0,
            'status' => 1,
        ]);

        
    }
}
