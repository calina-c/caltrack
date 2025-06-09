<?php

namespace App\Http\Controllers;

use App\Services\DayStatsService;
use App\Services\FoodEntryService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FoodEntriesController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;

    public function getFoodEntries(FoodEntryService $foodEntryService, DayStatsService $dayStatsService)
    {
        // Retrieve all food entries
        $selectedDate = request()->query('date') ? Carbon::createFromFormat('Y-m-d', request()->query('date')) : now();

        $startDate = (clone $selectedDate)->startOfWeek(1);
        $endDate = (clone $startDate)->endOfWeek();

        $foodEntries = $foodEntryService->getGroupedFoodEntries($startDate, $endDate);

        $namesOfDays = [
            'Luni', 'Marți', 'Miercuri', 'Joi', 'Vineri', 'Sâmbătă', 'Duminică',
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
                'goals' => \App\Models\Goal::where([
                    'date' => $currentDate->format('Y-m-d'),
                ])->get()->keyBy('goal_type_id'),
            ]);
            $currentDate->addDay();
        }

        return view('food_entries', [
            'foodEntries' => $allWeekDates,
            'foodItems' => \App\Models\FoodItem::orderBy('name')->get(),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedDay' => $selectedDate,
            'selectedDayName' => $namesOfDays[($selectedDate->dayOfWeek + 6) % 7],
            'goalTypes' => \App\Models\GoalType::where('is_current', true)->get(),
            'roMonthNames' => [
                '01' => 'Ianuarie',
                '02' => 'Februarie',
                '03' => 'Martie',
                '04' => 'Aprilie',
                '05' => 'Mai',
                '06' => 'Iunie',
                '07' => 'Iulie',
                '08' => 'August',
                '09' => 'Septembrie',
                '10' => 'Octombrie',
                '11' => 'Noiembrie',
                '12' => 'Decembrie',
            ],
        ]);
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

    /**
     * Handle the incoming request.
     *
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
            'direct_name' => 'required_if:food_item_id,null',
        ]);

        // Create a new food entry
        $validatedData['ate_at'] = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validatedData['date'].' '.$validatedData['time']
        );
        $foodEntry = new \App\Models\FoodEntry($validatedData);

        // Save the food entry to the database
        $foodEntry->save();

        return redirect()->route(
            'food-entries.index',
            ['date' => $foodEntry->ate_at->format('Y-m-d')]
        )->with(
            'success',
            'Adăugat cu succes intrarea alimentară: '.$foodEntry->direct_name ?: $foodEntry->foodItem->name
        )->withPreviousInput($request->all());
    }
}
