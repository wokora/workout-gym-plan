<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use App\Models\Food\Category;
use App\Models\Food\Food;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * Display a paginated listing of foods with search and category filtering.
     */
    public function index(Request $request): JsonResponse
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

        $perPage = (int) $request->input('per_page', 24);
        $foods = $query->paginate($perPage);
        $categories = Category::withCount('foods')->orderBy('name', 'asc')->get();
        $totalCount = Food::count();

        return response()->json([
            'records' => FoodResource::collection($foods),
            'total_count' => $totalCount,
            'categories' => $categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'foods_count' => $c->foods_count]),
            'pagination' => [
                'current_page' => $foods->currentPage(),
                'last_page'    => $foods->lastPage(),
                'per_page'     => $foods->perPage(),
                'total'        => $foods->total(),
            ]
        ]);
    }

    /**
     * Display categories listing.
     */
    public function categories(): JsonResponse
    {
        $categories = Category::withCount('foods')->orderBy('name', 'asc')->get();

        return response()->json([
            'records' => $categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'foods_count' => $c->foods_count])
        ]);
    }

    /**
     * Store a newly created food item.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'energy_kcal' => 'nullable|numeric|min:0',
            'energy_kj' => 'nullable|numeric|min:0',
            'water' => 'nullable|numeric|min:0',
            'protein' => 'nullable|numeric|min:0',
            'fat' => 'nullable|numeric|min:0',
            'carbs' => 'nullable|numeric|min:0',
            'fibre' => 'nullable|numeric|min:0',
            'ash' => 'nullable|numeric|min:0',
            'ca' => 'nullable|numeric|min:0',
            'mg' => 'nullable|numeric|min:0',
            'fe' => 'nullable|numeric|min:0',
            'na' => 'nullable|numeric|min:0',
            'vit_a' => 'nullable|numeric|min:0',
            'folate' => 'nullable|numeric|min:0',
            'vit_c' => 'nullable|numeric|min:0',
            'standard_portion_description' => 'nullable|string|max:255',
            'standard_portion_grams' => 'nullable|numeric|min:0',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:category,id',
        ]);

        $food = Food::create($validated);

        if ($request->has('category_ids') && is_array($request->category_ids)) {
            $food->categories()->sync($request->category_ids);
        }

        $food->load('categories');

        return response()->json([
            'message' => 'Food created successfully.',
            'food' => new FoodResource($food),
        ], 201);
    }

    /**
     * Display the specified food item.
     */
    public function show($id): JsonResponse
    {
        $food = Food::with(['categories', 'diseases'])->findOrFail($id);

        $firstCategory = $food->categories->first();
        $relatedFoods = collect();
        if ($firstCategory) {
            $relatedFoods = Food::whereHas('categories', function ($q) use ($firstCategory) {
                $q->where('category.id', $firstCategory->id);
            })
            ->where('id', '!=', $food->id)
            ->limit(5)
            ->get();
        }

        return response()->json([
            'food' => new FoodResource($food),
            'related_foods' => FoodResource::collection($relatedFoods),
        ]);
    }

    /**
     * Update the specified food item.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $food = Food::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'energy_kcal' => 'nullable|numeric|min:0',
            'energy_kj' => 'nullable|numeric|min:0',
            'water' => 'nullable|numeric|min:0',
            'protein' => 'nullable|numeric|min:0',
            'fat' => 'nullable|numeric|min:0',
            'carbs' => 'nullable|numeric|min:0',
            'fibre' => 'nullable|numeric|min:0',
            'ash' => 'nullable|numeric|min:0',
            'ca' => 'nullable|numeric|min:0',
            'mg' => 'nullable|numeric|min:0',
            'fe' => 'nullable|numeric|min:0',
            'na' => 'nullable|numeric|min:0',
            'vit_a' => 'nullable|numeric|min:0',
            'folate' => 'nullable|numeric|min:0',
            'vit_c' => 'nullable|numeric|min:0',
            'standard_portion_description' => 'nullable|string|max:255',
            'standard_portion_grams' => 'nullable|numeric|min:0',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:category,id',
        ]);

        $food->update($validated);

        if ($request->has('category_ids') && is_array($request->category_ids)) {
            $food->categories()->sync($request->category_ids);
        }

        $food->load('categories');

        return response()->json([
            'message' => 'Food updated successfully.',
            'food' => new FoodResource($food),
        ]);
    }

    /**
     * Remove the specified food item.
     */
    public function destroy($id): JsonResponse
    {
        $food = Food::findOrFail($id);
        $food->categories()->detach();
        $food->delete();

        return response()->json([
            'message' => 'Food deleted successfully.',
        ]);
    }

    /**
     * Search endpoint for food autocomplete.
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        $foods = Food::where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(30)
            ->get();

        return response()->json([
            'records' => FoodResource::collection($foods)
        ]);
    }
}
