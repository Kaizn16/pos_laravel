<?php

namespace App\Http\Controllers;

use App\Models\UnitOfMeasure;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use Illuminate\Support\Facades\Cache;

class UnitOfMeasureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageSize = $request->input('pageSize', 10);

        // Query to fetch categories
        $query = UnitOfMeasure::where('is_deleted', 0);

        $uoms = $query->orderBy('uom_id', 'desc')->paginate($pageSize);

        if ($request->ajax()) {
            return view('Inventory.uom', compact('uoms', 'pageSize'))->render();
        }

        return view('Inventory.uom', compact('uoms', 'pageSize'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $query = UnitOfMeasure::where('is_deleted', 0);
        $uoms = $query->orderBy('uom_id', 'desc')->get();

        session(['view_title' => 'New Unit Of Measure']);
        return view('Inventory.uom-form', compact('uoms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'uom_name' => 'required|unique:uom',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $uom = UnitOfMeasure::create([
            'uom_name' => $request->input('uom_name'),
            'description' => $request->input('description'),
            'status' => $request->input('status', 0),
        ]);

        if ($uom) {
            return response()->json(['message' => 'Unit Of Measure saved successfully!'], 200);
        } else {
            return response()->json(['message' => 'Unable to save Unit Of Measure. Please try again.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uom_id)
    {
        $uom = UnitOfMeasure::findOrFail($uom_id);

        if (!$uom) {
            return redirect()->route('Inventory.uom')->with('message', 'Unit of Measure not found.');
        }

        session(['view_title' => 'Edit Unit Of Measure']);
        return view('Inventory.uom-form', compact('uom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uom_id)
    {
        $request->validate([
            'uom_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);
    
        $uom = UnitOfMeasure::findOrFail($uom_id);
        $uom->uom_name = $request->uom_name;
        $uom->description = $request->description;
        $uom->status = $request->status;   
        $uom->save();
    
        return response()->json(['message' => 'Unit Of Measure updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function delete($uom_id)
    {
        $uom = UnitOfMeasure::findOrFail($uom_id);
        $uom->is_deleted = 1;
        $uom->save();
    }

    public function getUnitOfMeasures(Request $request)
    {
        $query = UnitOfMeasure::where('is_deleted', 0);

        // History Request
        if ($request->has('history')) {
            $uoms = Cache::remember('unit_of_measures_history', 30, function () use ($query) {
                return $query->orderBy('uom_id', 'desc')->limit(15)->get();
            });
            return response()->json($uoms);
        }

        // Table Request
        $pageSize = $request->input('pageSize', 10);
        $uoms = $query->orderBy('uom_id', 'desc')->paginate($pageSize);

        return response()->json($uoms);
    }
}
