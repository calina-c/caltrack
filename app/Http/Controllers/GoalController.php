<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function update(Request $request, $goal_type_id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'qty' => 'required|numeric',
        ]);

        $goal = \App\Models\Goal::updateOrCreate(
            [
                'date' => $validatedData['date'],
                'goal_type_id' => $goal_type_id,
            ],
            ['qty' => $validatedData['qty']]
        );

        return redirect(route('food-entries.index', ['date' => $validatedData['date']]));
    }
}
