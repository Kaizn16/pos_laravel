<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Svg\Tag\Rect;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageSize = $request->input('pageSize', 10); // Default page Size

        // Query to fetch categories
        $query = Category::where('is_deleted', 0);

        $categories = $query->orderBy('category_id', 'desc')->paginate($pageSize);

        if ($request->ajax()) {
            return view('Inventory.categories', compact('categories', 'pageSize'))->render();
        }

        return view('Inventory.categories', compact('categories', 'pageSize'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $query = Category::where('is_deleted', 0);
        $categories = $query->orderByRaw("SUBSTR(category_name, 1, 1)")->get();

        session(['view_title' => 'New Category']);
        return view('Inventory.category-form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:category,category_name',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $category = Category::create([
            'category_name' => $request->input('category_name'),
            'description' => $request->input('description'),
            'status' => $request->input('status', 0),
        ]);

        if ($category) {
            return response()->json(['message' => 'Category saved successfully!'], 200);
        } else {
            return response()->json(['message' => 'Unable to save category. Please try again.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($category_id)
    {
        $category = Category::findOrFail($category_id);

        if (!$category) {
            return redirect()->route('Inventory.categories')->with('message', 'Category not found.');
        }

        session(['view_title' => 'Edit Category']);
        return view('Inventory.category-form', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $category_id)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:category,category_name,' . $category_id . ',category_id',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);
    
        $category = Category::findOrFail($category_id);
        $category->category_name = $request->category_name;
        $category->description = $request->description;
        $category->status = $request->status;   
        $category->save();
    
        return response()->json(['message' => 'Category updated successfully!']);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function delete($category_id)
    {    
        $category = Category::findOrFail($category_id);
        $category->is_deleted = 1;   
        $category->save();
    
        return response()->json(['message' => 'Category deleted successfully!']);
    }

    // FETCB CATEGORIES
    public function getCategories(Request $request)
    {
        $query = Category::where('is_deleted', 0);

        // History Request
        if ($request->has('history')) {
            $categories = $query->orderBy('category_id', 'desc')->limit(15)->get();
            return response()->json($categories);
        }

        // Table Request
        $pageSize = $request->input('pageSize', 10);
        $categories = $query->orderBy('category_id', 'desc')->paginate($pageSize);

        return response()->json($categories);
    }
}
