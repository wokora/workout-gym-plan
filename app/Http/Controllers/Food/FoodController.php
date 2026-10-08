<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Models\Food\Category;
use App\Models\Food\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * Display a paginated listing of foods with search and category filtering.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Food::with('categories');

        if ($request->filled('q')) {
            $search = trim($request->get('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $categoryId = $request->get('category_id');
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('category.id', $categoryId);
            });
        }

        $sort = $request->get('sort', 'name_asc');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'calories_desc':
                $query->orderBy('energy_kcal', 'desc');
                break;
            case 'calories_asc':
                $query->orderBy('energy_kcal', 'asc');
                break;
            case 'protein_desc':
                $query->orderBy('protein', 'desc');
                break;
            case 'carbs_desc':
                $query->orderBy('carbs', 'desc');
                break;
            case 'fat_desc':
                $query->orderBy('fat', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $foods = $query->paginate(24)->withQueryString();
        $categories = Category::withCount('foods')->orderBy('name', 'asc')->get();
        $totalFoodsCount = Food::count();

        return view('food.index', compact('foods', 'categories', 'totalFoodsCount'));
    }

    /**
     * Display the specified food nutrient profile.
     *
     * @param int|string $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $food = Food::with('categories')->findOrFail($id);

        // Fetch related foods in the same category
        $categoryIds = $food->categories->pluck('id')->toArray();
        $relatedFoods = collect();
        if (!empty($categoryIds)) {
            $relatedFoods = Food::where('id', '!=', $food->id)
                ->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('category.id', $categoryIds);
                })
                ->limit(6)
                ->get();
        }

        return view('food.show', compact('food', 'relatedFoods'));
    }

    /**
     * Dynamic AJAX autocomplete search endpoint for meal planner.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
