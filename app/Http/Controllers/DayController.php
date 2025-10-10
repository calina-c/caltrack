<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;

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

        $mapping = [
            1 => 'Jale extremală',
            2 => 'Nasol',
            3 => 'Meh',
            4 => 'OK',
            5 => 'Forță',
        ];

        $notification = Notification::create([
            'user_id' => User::where('Name', 'Călina')->first()->id,
            'description' => "Ai primit rating de {$mapping[$day->rating]} pentru ziua de {$day->date->format('Y-m-d')}.",
            'data' => json_encode([
                'day_id' => $day->id,
                'rating' => $day->rating,
                'date' => $day->date->format('Y-m-d'),
            ]),
        ]);
        $notification->save();

        return Inertia::location(route('food-entries.index', ['date' => $day->date->addDay()->format('Y-m-d')]));
    }
}
