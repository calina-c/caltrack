<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ThumbsController extends Controller
{
    /**
     * Store a new thumb.
     */
    public function addThumb(Request $request)
    {
        $validated = $request->validate([
            'food_item_id' => 'nullable|exists:food_items,id',
            'food_entry_id' => 'nullable|exists:food_entries,id',
            'type' => 'required|in:up,down',
        ]);

        $date = $request->input('date');

        $thumb = \App\Models\Thumbs::create($validated);

        return redirect(route('food-entries.index', ['date' => $date]));
    }
}
