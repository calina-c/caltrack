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

    public function getFoodEntries(FoodEntryService $foodEntryService, DayStatsService $dayStatsService)
    {
        // Retrieve all food entries
        $selectedDate = request()->query('date') ? Carbon::createFromFormat("Y-m-d", request()->query('date')) : now();

        $startDate = (clone $selectedDate)->startOfWeek(1);
        $endDate = (clone $startDate)->endOfWeek();

        $foodEntries = $foodEntryService->getGroupedFoodEntries($startDate, $endDate);

        $namesOfDays = [
            'Luni', 'Marți', 'Miercuri', 'Joi', 'Vineri', 'Sâmbătă', 'Duminică'
        ];

        $allWeekDates = collect();
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $entries = $foodEntries->get($currentDate->format('Y-m-d'), collect());
            $sumKcal = $entries->sum('kcal');
            $sumProtein = $entries->sum('protein');

            $allWeekDates->push([
                'entries' => $entries,
                'sumKcal' => $sumKcal,
                'sumProtein' => $sumProtein,
                'kcalClass' => $dayStatsService->getKcalRangeLabel($sumKcal),
                'proteinClass' => $dayStatsService->getProteinRangeLabel($sumProtein),
                'date' => (clone $currentDate),
                'dayNameHuman' => $namesOfDays[($currentDate->dayOfWeek + 6) % 7],
                'dayObject' => \App\Models\Day::where([
                    'date' => $currentDate->format('Y-m-d'),
                ])->first(),
            ]);
            $currentDate->addDay();
        }

        return view('food_entries', [
            'foodEntries' => $allWeekDates,
            # TODO: selectize
            # for selector to create new food entries
            'foodItems' => \App\Models\FoodItem::orderBy('name')->get(),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedDay' => $selectedDate,
            'selectedDayName' => $namesOfDays[($selectedDate->dayOfWeek + 6) % 7],
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
            'food_item_id' => 'sometimes',
            'multiplier' => 'required_unless:food_item_id,null|numeric|min:0',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'description' => 'nullable|string|max:255',
            'direct_kcal' => 'required_if:food_item_id,null',
            'direct_protein' => 'required_if:food_item_id,null',
            'direct_name' => 'required_if:food_item_id,null'
        ]);

        // Create a new food entry
        $validatedData['ate_at'] = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validatedData['date'] . ' ' . $validatedData['time']
        );
        $foodEntry = new \App\Models\FoodEntry($validatedData);

        // Save the food entry to the database
        $foodEntry->save();

        return redirect()->route(
            'food-entries.index',
            ['date' => $foodEntry->ate_at->format('Y-m-d')]
        )->with(
            'success',
            'Adăugat cu succes intrarea alimentară: ' . $foodEntry->direct_name ?: $foodEntry->foodItem->name
        )->withPreviousInput($request->all());
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

    public function addDay(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'done' => 'required|boolean',
        ]);

        // Create a new day entry
        $day = new \App\Models\Day($validatedData);

        // Save the day to the database
        $day->save();

        return redirect()->route('food-entries.index', ['date' => $day->date->format("Y-m-d")])->with(
            'success',
            'Ziua a fost încheiată.'
        )->withPreviousInput($request->all());
    }

    public function editDay(Request $request, $id)
    {
        // Find the day by ID
        $day = \App\Models\Day::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:0|max:5',
        ]);

        // Update the day entry
        $day->rating = $validatedData['rating'];
        $day->save();

        return redirect()->route('food-entries.index', ['date' => $day->date->format("Y-m-d")])->with(
            'success',
            'Ai dat rating.'
        )->withPreviousInput($request->all());
    }

    public function getFoodItemFormat($id, FoodEntryService $foodEntryService)
    {
        // Find the food item by ID
        $foodItem = \App\Models\FoodItem::findOrFail($id);
        $entries = collect([$foodItem]);
        $entries = $foodEntryService->addQtyForHumans(
            $entries,
            "unit_name",
            "unit_base_quantity"
        );

        // Return the food item format as a JSON response
        return response()->json($entries->first(), 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
