<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Cart;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function generateNewTransaction(Request $request)
    {
        // Fetch the pending transaction, if any
        $pendingTransaction = Transaction::where('status', 'pending')->first();

        if ($pendingTransaction) {
            return response()->json([
                'success' => false,
                'transaction_id' => $pendingTransaction->transaction_id,
                'transaction_number' => $pendingTransaction->transaction_number,
            ], 200);
        }

        $transaction = new Transaction();
        $transactionNumber = $transaction->generateTransactionNumber();
        $transaction->transaction_number = $transactionNumber;

        if ($transaction->save()) {
            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->transaction_id,
                'transaction_number' => $transactionNumber,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create transaction',
            ], 500);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
}
