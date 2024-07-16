<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'transaction_id' => 'required',
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer|min:1',
            'product_price' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        $cartItem = Cart::where('product_id', $validatedData['product_id'])
                        ->where('transaction_id', $validatedData['transaction_id'])
                        ->first();

        if ($cartItem) {
            // Product exists in cart, increment quantity and update total
            $cartItem->quantity += $validatedData['quantity'];
            $cartItem->total += $validatedData['total'];
            $cartItem->save();
        } else {
            // Product does not exist in cart, create new entry
            $cartItem = Cart::create([
                'transaction_id' => $validatedData['transaction_id'],
                'product_id' => $validatedData['product_id'],
                'quantity' => $validatedData['quantity'],
                'product_price' => $validatedData['product_price'],
                'total' => $validatedData['total'],
            ]);
        }

        return response()->json(['message' => 'Product added to cart successfully', 'cart_item' => $cartItem], 200);
    }

    public function show($transaction_id)
    {
        $carts = Cart::where('transaction_id', $transaction_id)
                    ->with('product')
                    ->orderBy('cart_id', 'asc')
                    ->get();

        if ($carts->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 200);
        }
        // If carts are found, return them as JSON
        return response()->json($carts, 200);
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
    public function update(Request $request, $product_id)
    {
        $request->validate([
            'transaction_id' => 'required',
            'action' => 'required|in:increase,decrease',
        ]);

        $transaction_id = $request->input('transaction_id');
        $action = $request->input('action');

        $cartItem = Cart::where('product_id', $product_id)
                        ->where('transaction_id', $transaction_id)
                        ->firstOrFail();

        $product = Product::findOrFail($product_id);
        $product_price = $product->selling_price;

        if ($action === 'increase') {
            $cartItem->quantity += 1;
            $cartItem->total += $product_price;
        } elseif ($action === 'decrease') {
            $cartItem->quantity -= 1;
            if ($cartItem->quantity < 0) {
                $cartItem->quantity = 0;
            }
            $cartItem->total -= $product_price;
            if ($cartItem->total < 0) {
                $cartItem->total = 0;
            }
        }
        if ($cartItem->quantity === 0) {
            $cartItem->delete();
        } else {
            $cartItem->save();
        }

        // Return response
        return response()->json(['message' => 'Quantity and total updated successfully.']);
    }

    public function delete($product_id)
    {
        $cart = Cart::where('product_id', $product_id)->firstOrFail();

        if($cart->delete())
        {
            return response()->json(['message' => 'Product removed from cart successfully.']);
        }

        return response()->json(['message' => 'Unable to remove product from the cart.']);
    }
    
    public function clear($transaction_id)
    {
        $clear_cart = Cart::where('transaction_id', $transaction_id)->delete();
    
        if($clear_cart)
        {
            return response()->json(['message' => 'Cart cleared successfully.']);
        }

        return response()->json(['message' => 'The cart is already empty!.']);
    }
}