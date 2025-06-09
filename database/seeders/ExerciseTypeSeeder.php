<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExerciseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Gym Cristi',
                'icon' => 'medal',
            ],
            [
                'name' => 'Gym',
                'icon' => 'dumbbell',
            ],
            [
                'name' => 'Running',
                'icon' => 'person-running',
            ],
            [
                'name' => 'Cycling',
                'icon' => 'bicycle',
            ],
            [
                'name' => 'Trail Run',
                'icon' => 'mountain-sun',
            ],
            [
                'name' => 'Yoga/Stretching',
                'icon' => 'spa',
            ],
            [
                'name' => 'At home',
                'icon' => 'house',
            ],
        ];

        foreach ($data as $item) {
            if (\App\Models\ExerciseType::where('name', $item['name'])->exists()) {
                continue; // Skip if goal type already exists
            }

            \App\Models\ExerciseType::create($item);
        }
    }
}
