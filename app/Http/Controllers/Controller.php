<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Services\DayStatsService;
use App\Services\FoodEntryService;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function addFoodEntry($request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'multiplier' => 'required|numeric|min:0',
            'datetime' => 'required|date',
        ]);

        // Create a new food entry
        $foodEntry = new \App\Models\FoodEntry($validatedData);

        // Save the food entry to the database
        $foodEntry->save();

        return response()->json(['message' => 'Food entry added successfully', 'entry' => $foodEntry], 201);
    }

    public function getFoodEntries(FoodEntryService $foodEntryService, DayStatsService $dayStatsService)
    {
        // Retrieve all food entries
        $startDate = request()->query('date') ?
            Carbon::createFromFormat("Y-m-d", request()->query('date'))->startOfWeek(1) :
        now()->startOfWeek(1);

        $endDate = clone $startDate;
        $endDate->endOfWeek();

        $foodEntries = $foodEntryService->getGroupedFoodEntries($startDate, $endDate);

        $namesOfDays = [
            'Luni', 'Marți', 'Miercuri', 'Joi', 'Vineri', 'Sâmbătă', 'Duminică'
        ];

        $namesOfMonths = [
            'Ianuarie', 'Februarie', 'Martie', 'Aprilie', 'Mai', 'Iunie',
            'Iulie', 'August', 'Septembrie', 'Octombrie', 'Noiembrie', 'Decembrie'
        ];

        $allWeekDates = collect();
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $entries = $foodEntries->get($currentDate->format('Y-m-d'), collect());
            $sumKcal = $entries->sum('kcal');
            $sumProtein = $entries->sum('protein');

            $allWeekDates->push([
                'date' => $currentDate->format('j') . ' ' . $namesOfMonths[$currentDate->month - 1],
                'dayNameHuman' => $namesOfDays[($currentDate->dayOfWeek + 6) % 7],
                'today' => $namesOfDays[(now()->dayOfWeek + 6) % 7],
                'entries' => $entries,
                'sumKcal' => $sumKcal,
                'sumProtein' => $sumProtein,
                'kcalClass' => $dayStatsService->getKcalRangeLabel($sumKcal),
                'proteinClass' => $dayStatsService->getProteinRangeLabel($sumProtein),
            ]);
            $currentDate->addDay();
        }

        return view('food_entries', [
            'foodEntries' => $allWeekDates,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function getFoodItems(FoodEntryService $foodEntryService)
    {
        // Retrieve all food items
        $foodItems = \App\Models\FoodItem::all();
        $foodItems = $foodEntryService->getQtyForHumans(
            $foodItems,
            "unit_name",
            "unit_base_quantity"
        );

        return view('food_items', [
            'foodItems' => $foodItems,
        ]);
    }


    public function addFoodItem(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:food_items,name',
            'brand' => 'nullable|string|max:255',
            'unit_name' => 'required|string|max:50',
            'unit_base_quantity' => 'required|numeric|min:0',
            'kcal' => 'required|numeric|min:0',
            'protein' => 'required|numeric|min:0',
        ]);

        // Create a new food item
        $foodItem = new \App\Models\FoodItem($validatedData);

        // Save the food item to the database
        $foodItem->save();

        return redirect()->route('food-items.index')->with(
            'success', 'Adăugat cu succes alimentul: ' . $foodItem->name
        )->withPreviousInput($request->all());
    }
}
