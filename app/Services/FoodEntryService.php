<?php

namespace App\Services;

use DB;
use App\Models\FoodEntry;

class FoodEntryService
{
    public function getGroupedFoodEntries($startDate = null, $endDate = null)
    {
        $foodEntries = DB::table('food_entries')->join('food_items', 'food_entries.food_item_id', '=', 'food_items.id')
            ->selectRaw(
                'DATE(ate_at) as ate_at_date, HOUR(ate_at) as ate_at_hour, MINUTE(ate_at) as ate_at_min, '.
                'multiplier * food_items.unit_base_quantity as total_quantity, '.
                'food_items.unit_name, food_items.name as food_name, '.
                'multiplier * food_items.kcal as kcal, '.
                'multiplier * food_items.protein as protein'
            )
            ->whereBetween('ate_at', [$startDate->format("Y-m-d"), $endDate->format("Y-m-d")])
            ->orderBy('ate_at')
            ->get();

        $foodEntries = $foodEntries->map(function ($entry) {
            # TODO: more implementations
            switch ($entry->unit_name) {
                case 'g':
                    $entry->qtyForHumans = $entry->total_quantity . 'g';
                    break;
                case 'buc':
                    $entry->qtyForHumans = (int)$entry->total_quantity . ' buc';
                    break;
                default:
                    $entry->qtyForHumans = $entry->total_quantity . ' ' . $entry->unit_name;
            }

            return $entry;
        });

        // Format the results
        return $foodEntries->groupBy('ate_at_date');
    }

    public function getQtyForHumans($entries, $unit_name, $unit_quantity)
    {
        $entries = $entries->map(function ($entry) use ($unit_name, $unit_quantity) {
            # TODO: more implementations
            switch ($entry->{$unit_name}) {
                case 'g':
                    $entry->qtyForHumans = $entry->{$unit_quantity} . 'g';
                    break;
                case 'buc':
                    $entry->qtyForHumans = (int)$entry->{$unit_quantity} . ' buc';
                    break;
                default:
                    $entry->qtyForHumans = $entry->{$unit_quantity} . ' ' . $entry->unit_name;
            }

            return $entry;
        });

        return $entries;
    }
}
