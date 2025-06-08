<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GoalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Creatină',
                'target_qty' => 1,
                'is_current' => true,
            ],
            [
                'name' => 'Suplimente',
                'target_qty' => 2,
                'is_current' => true,
            ],
            [
                'name' => 'Apă',
                'target_qty' => 8,
                'is_current' => true,
            ],
        ];

        foreach ($data as $item) {
            if (\App\Models\GoalType::where('name', $item['name'])->exists()) {
                continue; // Skip if goal type already exists
            }

            \App\Models\GoalType::create($item);
        }
    }
}
