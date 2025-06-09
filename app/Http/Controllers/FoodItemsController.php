<?php

namespace App\Http\Controllers;

use App\Services\FoodEntryService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FoodItemsController extends Controller
{
    public function getFoodItems(FoodEntryService $foodEntryService)
    {
        $searchQuery = request()->query('search');

        // Retrieve all food items
        $foodItems = \App\Models\FoodItem::withCount('entries')
            ->orderBy('name')
            ->when($searchQuery, function ($query) use ($searchQuery) {
                return $query->where('name', 'like', '%'.$searchQuery.'%')
                    ->orWhere('brand', 'like', '%'.$searchQuery.'%');
            })
            ->get();

        $foodItems = $foodEntryService->addQtyForHumans(
            $foodItems,
            'unit_name',
            'unit_base_quantity'
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
            'Adăugat cu succes alimentul: '.$foodItem->name
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

    public function getFoodItemFormat($id, FoodEntryService $foodEntryService)
    {
        // Find the food item by ID
        $foodItem = \App\Models\FoodItem::findOrFail($id);
        $entries = collect([$foodItem]);
        $entries = $foodEntryService->addQtyForHumans(
            $entries,
            'unit_name',
            'unit_base_quantity'
        );

        // Return the food item format as a JSON response
        return response()->json($entries->first(), 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
