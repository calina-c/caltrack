<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DayController extends Controller
{
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

        return redirect()->route('food-entries.index', ['date' => $day->date->format('Y-m-d')])->with(
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

        return redirect()->route('food-entries.index', ['date' => $day->date->format('Y-m-d')])->with(
            'success',
            'Ai dat rating.'
        )->withPreviousInput($request->all());
    }
}
