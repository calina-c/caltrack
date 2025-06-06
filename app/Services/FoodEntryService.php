<?php

namespace App\Services;

use Carbon\Carbon;
use DB;
use App\Models\FoodEntry;

class FoodEntryService
{
    public function getGroupedFoodEntries($startDate = null, $endDate = null)
    {
        $foodEntries = DB::table('food_entries')
            ->leftJoin('food_items', 'food_entries.food_item_id', '=', 'food_items.id')
            ->selectRaw(
                'food_entries.id, '.
                'food_entries.description, '.
                'food_entries.ate_at, '.
                'food_items.unit_name, '.
                'COALESCE(direct_name, food_items.name) as food_name, '.
                'COALESCE(multiplier * food_items.unit_base_quantity, NULL) as total_quantity, '.
                'COALESCE(direct_kcal, multiplier * food_items.kcal) as kcal, '.
                'COALESCE(direct_protein, multiplier * food_items.protein) as protein '
            )
            ->where('ate_at', '>=', $startDate->format("Y-m-d"))
            ->where('ate_at', '<=', $endDate->format("Y-m-d") . ' 23:59:59')
            ->orderBy('ate_at')
            ->get();

        $foodEntries = $foodEntries->map(function ($entry) {
            $entry->ate_at = Carbon::parse($entry->ate_at);
            $entry->ate_at_date = $entry->ate_at->format('Y-m-d');

            return $entry;
        });

        $foodEntries = $this->addQtyForHumans($foodEntries, 'unit_name', 'total_quantity');

        // Format the results
        return $foodEntries->groupBy('ate_at_date');
    }

    public function addQtyForHumans($entries, $unit_name, $unit_quantity)
    {
        $entries = $entries->map(function ($entry) use ($unit_name, $unit_quantity) {
            if (!isset($entry->{$unit_name}) || !isset($entry->{$unit_quantity})) {
                $entry->qtyForHumans = 'N/A';
                return $entry;
            }

            $unitName = $entry->{$unit_name};
            $quantity = $entry->{$unit_quantity};

            $pluralized = [
                'ceașcă' => 'cești',
                'felie' => 'felii',
                'porție' => 'porții',
                'lingură' => 'linguri',
                'buc' => 'buc',
            ];

            if ($quantity != 1 && $quantity != 0.5 && isset($pluralized[$unitName])) {
                $unitName = $pluralized[$unitName];
            }

            if ($quantity == 1 && array_key_exists($unitName, $pluralized)) {
                $quantity = 'o';
            } elseif ($quantity == 0.5 && array_key_exists($unitName, $pluralized)) {
                $quantity = '<sup>1</sup>&frasl;<sub>2</sub>';
                $quantity = $unitName == 'buc' ? $quantity : $quantity . ' de';
            } else {
                if ((int) ($quantity * 100) % 100) {
                    $quantity = number_format($quantity, 2, ',', '');
                } else {
                    $quantity = (int) $quantity;
                }
            }

            switch ($unitName) {
                case 'g':
                    $entry->qtyForHumans = $quantity . 'g';
                    break;
                default:
                    $entry->qtyForHumans = $quantity . ' ' . $unitName;
            }

            return $entry;
        });

        return $entries;
    }
}
