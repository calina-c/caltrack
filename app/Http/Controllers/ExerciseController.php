<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExerciseController extends Controller
{
    public function addExercise(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'date' => 'required|date',
            'exercise_type_id' => 'required|exists:exercise_types,id',
            'label' => 'sometimes'
        ]);

        // Create a new exercise record
        $exercise = new \App\Models\Exercise();
        $exercise->date = $request->input('date');
        $exercise->exercise_type_id = $request->input('exercise_type_id');
        $exercise->label = $request->input('label');
        $exercise->save();

        return redirect(route('food-entries.index', ['date' => $validatedData['date']]));
    }
}
