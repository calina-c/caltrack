<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FoodItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'ou fiert',
                'brand' => null,
                'unit_name' => 'buc',
                'unit_base_quantity' => 1.00,
                'protein' => 5.50,
                'kcal' => 68.00,
            ],
            [
                'name' => 'lapte 1.5%',
                'brand' => null,
                'unit_name' => 'ml',
                'unit_base_quantity' => 100.00,
                'protein' => 3.40,
                'kcal' => 46.00,
            ],
            [
                'name' => 'lapte 3.5%',
                'brand' => null,
                'unit_name' => 'ml',
                'unit_base_quantity' => 100.00,
                'protein' => 3.40,
                'kcal' => 64.00,
            ],
            [
                'name' => 'budincă High Protein ciocolată',
                'brand' => 'LIDL - Milbona',
                'unit_name' => 'g',
                'unit_base_quantity' => 100.00,
                'protein' => 10.00,
                'kcal' => 80.00,
            ],
            [
                'name' => 'budincă High Protein vanilie',
                'brand' => 'LIDL - Milbona',
                'unit_name' => 'g',
                'unit_base_quantity' => 100.00,
                'protein' => 10.00,
                'kcal' => 80.00,
            ],
            [
                'name' => 'budincă High Protein caramel',
                'brand' => 'LIDL - Milbona',
                'unit_name' => 'g',
                'unit_base_quantity' => 100.00,
                'protein' => 10.00,
                'kcal' => 80.00,
            ],
            [
                'name' => 'migdale crude',
                'brand' => null,
                'unit_name' => 'pumn',
                'unit_base_quantity' => 1,
                'protein' => 2.70,
                'kcal' => 77.00,
            ],
            [
                'name' => 'cappuccino specialty',
                'brand' => null,
                'unit_name' => 'ceașcă',
                'unit_base_quantity' => 1.00,
                'protein' => 4,
                'kcal' => 80.00,
            ],
            [
                'name' => 'baton proteic ciocolată',
                'brand' => 'Optimum Nutrition',
                'unit_name' => 'buc',
                'unit_base_quantity' => 1.00,
                'protein' => 18.00,
                'kcal' => 221.00,
            ],
            [
                'name' => 'pâine de secară',
                'brand' => 'KB',
                'unit_name' => 'felie',
                'unit_base_quantity' => 1.00,
                'protein' => 2.50,
                'kcal' => 60.00,
            ],
            [
                'name' => 'MD clasic',
                'brand' => null,
                'unit_name' => 'porție',
                'unit_base_quantity' => 1.00,
                'protein' => 34.9,
                'kcal' => 399,
            ],
            [
                'name' => 'salată cu oțet balsamic și zahăr brun',
                'brand' => null,
                'unit_name' => 'porție',
                'unit_base_quantity' => 1.00,
                'protein' => 3,
                'kcal' => 52.00,
            ],
            [
                'name' => 'sos sweet chilli',
                'brand' => null,
                'unit_name' => 'lingură',
                'unit_base_quantity' => 1.00,
                'protein' => 0.00,
                'kcal' => 55.00,
            ],
            [
                'name' => 'sos imitație aioli',
                'brand' => null,
                'unit_name' => 'lingură',
                'unit_base_quantity' => 1.00,
                'protein' => 0.00,
                'kcal' => 20.00,
            ],
            [
                'name' => 'roșie cherry',
                'brand' => null,
                'unit_name' => 'buc',
                'unit_base_quantity' => 1.00,
                'protein' => 0.10,
                'kcal' => 3.0,
            ],
            [
                'name' => 'căpșuni la bucată',
                'brand' => null,
                'unit_name' => 'buc',
                'unit_base_quantity' => 1.00,
                'protein' => 0.10,
                'kcal' => 4.00,
            ],
            [
                'name' => 'căpșuni la 100g',
                'brand' => null,
                'unit_name' => 'g',
                'unit_base_quantity' => 100.00,
                'protein' => 0.70,
                'kcal' => 32.00,
            ],
            [
                'name' => 'nectarine',
                'brand' => null,
                'unit_name' => 'buc',
                'unit_base_quantity' => 1.00,
                'protein' => 0.60,
                'kcal' => 34.00,
            ],
            [
                'name' => 'măr GS',
                'brand' => null,
                'unit_name' => 'buc',
                'unit_base_quantity' => 1.00,
                'protein' => 0.50,
                'kcal' => 95.00,
            ],
            [
                'name' => 'paneuri de pui',
                'brand' => null,
                'unit_name' => 'g',
                'unit_base_quantity' => 100.00,
                'protein' => 22.00,
                'kcal' => 190.00,
            ],
            [
                'name' => 'shake proteic Rochers cu lapte 1.5%',
                'brand' => 'Vitabolic',
                'unit_name' => 'porție',
                'unit_base_quantity' => 1,
                'protein' => 35.00,
                'kcal' => 234.00,
            ],
            [
                'name' => 'dovlecel la cuptor',
                'brand' => null,
                'unit_name' => 'buc',
                'unit_base_quantity' => 1,
                'protein' => 2.40,
                'kcal' => 34.00,
            ],
            [
                'name' => 'turmeric latte',
                'brand' => null,
                'unit_name' => 'ceașcă',
                'unit_base_quantity' => 1,
                'protein' => 3.40,
                'kcal' => 64.00,
            ],
            [
                'name' => 'ceai cu lapte',
                'brand' => null,
                'unit_base_quantity' => 1,
                'unit_name' => 'ceașcă',
                'unit_base_quantity' => 100.00,
                'protein' => 3.40,
                'kcal' => 64.00,
            ],
            [
                'name' => 'ton la conservă',
                'brand' => null,
                'unit_name' => 'g',
                'unit_base_quantity' => 100.00,
                'protein' => 23.00,
                'kcal' => 120.00,
            ],
            [
                'name' => 'aripioare de pui la cuptor',
                'brand' => null,
                'unit_name' => 'g',
                'unit_base_quantity' => 100.00,
                'kcal' => 240.00,
                'protein' => 18.00,
            ],
            [
                'name' => 'Peanut Butter HP cu salted caramel',
                'brand' => 'GO.ON',
                'unit_name' => 'g',
                'unit_base_quantity' => 100.00,
                'protein' => 33.00,
                'kcal' => 582.00,
            ],
        ];

        foreach ($data as $item) {
            if (\App\Models\FoodItem::where('name', $item['name'])
                ->where('brand', $item['brand'])
                ->exists()) {
                continue; // Skip if the item already exists
            }

            \App\Models\FoodItem::create(
                [
                    'unit_name' => $item['unit_name'],
                    'unit_base_quantity' => $item['unit_base_quantity'],
                    'protein' => $item['protein'],
                    'kcal' => $item['kcal'],
                ]
            );
        }
    }
}
