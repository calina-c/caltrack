<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Services\DayStatsService;
use App\Services\FoodEntryService;

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
}
