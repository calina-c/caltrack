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
    public function addFoodEntry(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'multiplier' => 'required|numeric|min:0',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'description' => 'nullable|string|max:255',
        ]);

        // Create a new food entry
        $validatedData['ate_at'] = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validatedData['date'] . ' ' . $validatedData['time']
        );
        $foodEntry = new \App\Models\FoodEntry($validatedData);

        // Save the food entry to the database
        $foodEntry->save();

        # TODO: redirect to the specific day, if another day is selected
        return redirect()->route('food-entries.index')->with(
            'success',
            'Adăugat cu succes intrarea alimentară: ' . $foodEntry->foodItem->name
        )->withPreviousInput($request->all());
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
                'datestring' => (clone $currentDate)->format('Y-m-d'),
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
            # TODO: selectize
            'foodItems' => \App\Models\FoodItem::orderBy('name')->get(),
            'todayDatestring' => now()->format('Y-m-d'),
        ]);
    }

    public function getFoodItems(FoodEntryService $foodEntryService)
    {
        // Retrieve all food items
        $foodItems = \App\Models\FoodItem::withCount('entries')
            ->orderBy('name')
            ->get();

        $foodItems = $foodEntryService->addQtyForHumans(
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
            'success',
            'Adăugat cu succes alimentul: ' . $foodItem->name
        )->withPreviousInput($request->all());
    }

    public function deleteFoodItem($id)
    {
        // Find the food item by ID
        $foodItem = \App\Models\FoodItem::findOrFail($id);

        if ($foodItem->entries()->count() > 0) {
            return redirect()->route('food-items.index')->with(
                'error',
                'Nu se poate șterge alimentul deoarece are intrări asociate.'
            );
        }

        // Delete the food item
        $foodItem->delete();

        return redirect()->route('food-items.index')->with(
            'success',
            'Alimentul a fost șters cu succes.'
        );
    }

    public function deleteFoodEntry($id)
    {
        // Find the food entry by ID
        $foodEntry = \App\Models\FoodEntry::findOrFail($id);

        // Delete the food entry
        $foodEntry->delete();

        return redirect()->route('food-entries.index')->with(
            'success',
            'Intrarea alimentară a fost ștearsă cu succes.'
        );
    }
}
