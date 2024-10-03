<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Cart;
use App\Models\Stock;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        // Validate the incoming request
        $request->validate([
            'transaction_id' => 'required|exists:transaction,transaction_id',
            'customer' => 'nullable|exists:customer,customer_id',
            'discount_id' => 'nullable|exists:discount,discount_id',
            'subtotal' => 'required|numeric|min:1',
            'discount_amount' => 'nullable|numeric|min:0',
            'grandtotal' => 'required|numeric|min:1',
            'pay_amount' => 'required|numeric|min:1',
            'change' => 'required|numeric|min:0',
            'payment_method' => 'required|exists:payment_method,payment_method_id',
        ]);

        // Retrieve data from the request
        $transaction_id = $request->input('transaction_id');
        $customer_id = $request->input('customer');
        $total_item = $request->input('total_item');
        $discount_id = $request->input('discount_id');
        $subtotal = $request->input('subtotal');
        $discount_amount = $request->input('discount_amount');
        $grandtotal = $request->input('grandtotal');
        $pay_amount = $request->input('pay_amount');
        $change_amount = $request->input('change');
        $payment_method = $request->input('payment_method');

        // Find the existing transaction record
        $transaction = Transaction::findOrFail($transaction_id);

        $currentTimestamp = Carbon::now();//CURRENT DATE AND TIME

        // Update the transaction record
        $transaction->update([
            'customer_id' => $customer_id,
            'total_item' => $total_item,
            'discount_id' => $discount_id,
            'discount_amount' => $discount_amount,
            'subtotal' => $subtotal,
            'grand_total' => $grandtotal,
            'pay_amount' => $pay_amount,
            'change_amount' => $change_amount,
            'payment_method_id' => $payment_method,
            'status' => 'completed',
            'transaction_date' => $currentTimestamp,
        ]);

        // Retrieve cart items
        $cartItems = Cart::where('transaction_id', $transaction_id)->get();

        foreach ($cartItems as $item) {
            TransactionDetail::create([
                'transaction_id' => $transaction_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'product_price' => $item->product_price,
                'total' => $item->quantity * $item->product_price,
            ]);
        
            // Decrement product stock
            $product_stock = Stock::where('product_id', $item->product_id)->firstOrFail();
            $product_stock->stock_amount -= $item->quantity;
            $product_stock->save();
        }
        

        // Clear the cart for the given transaction_id
        Cart::where('transaction_id', $transaction_id)->delete();

        // Fetch updated transaction and transaction details
        $transaction = Transaction::where('transaction_id', $transaction_id)->first();
        $transactionDetails = TransactionDetail::where('transaction_id', $transaction_id)->with('product')->get();

        // Respond with a JSON message
        return response()->json([
            'message' => 'Transaction successfully updated!',
            'transaction' => $transaction,
            'transaction_receipt' => $transactionDetails,
        ], 200);
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
