<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageSize = $request->input('pageSize', 10); // Default page Size

        // Query to fetch suppliers
        $query = Supplier::where('is_deleted', 0);

        $suppliers = $query->orderBy('supplier_id', 'desc')->paginate($pageSize);

        if ($request->ajax()) {
            return view('Inventory.suppliers', compact('suppliers', 'pageSize'))->render();
        }

        return view('Inventory.suppliers', compact('suppliers', 'pageSize'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        session(['view_title' => 'New Supplier']);
        return view('Inventory.supplier-form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'contact_number' => 'required|string|max:255',
            'status' => 'sometimes|boolean',
        ]);

        if (!isset($validated['status'])) {
            $validated['status'] = 0;
        }

        // Create a new supplier record
        $supplier = Supplier::create($validated);

        if ($supplier) {
            return response()->json(['message' => 'Supplier saved successfully!'], 200);
        } else {
            return response()->json(['message' => 'Unable to save supplier. Please try again.'], 500);
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $tableSize = $request->input('table_size', 10); // Default table size

        $suppliers = Supplier::where('is_deleted', 0)
                        ->where(function($q) use ($query) {
                            $q->where('supplier_name', 'like', "%$query%")
                            ->orWhere('address', 'like', "%$query%")
                            ->orWhere('email', 'like', "%$query%")
                            ->orWhere('contact_number', 'like', "%$query%");
                        })
                        ->orderBy('supplier_id', 'desc')
                        ->paginate($tableSize);

        return response()->json(['data' => $suppliers->items()]);
    }
}
