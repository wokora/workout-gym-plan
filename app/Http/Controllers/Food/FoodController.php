<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Models\Food\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $foods = Food::where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(30)
            ->get()
            ->map(function ($food) {
                return [
                    'id' => $food->id,
                    'name' => $food->name,
                    'energy_kcal' => $food->energy_kcal,
                    'protein' => $food->protein,
                    'carbs' => $food->carbs,
                    'fat' => $food->fat,
                ];
            });

        return response()->json($foods);
    }
}
