<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())
                    ->get();

        return view('cart', compact('carts'));
    }

    public function checkoutPage()
    {
        $carts = Cart::where('user_id', Auth::id())
                    ->get();

        if($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout', compact('carts'));
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
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $request->product_id)
                    ->first();

        if ($cart) {
            // If the product is already in the cart, update the quantity
            $cart->quantity += 1;
            $cart->save();
        } else {
            // Otherwise, create a new cart item
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => 1,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if($cart->user_id !== Auth::id() ) {
            return redirect()->route('cart.index')->with('error', 'Unauthorized action.');
        }

        $cart->quantity = $request->quantity;
        $cart->save();

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        if($cart->user_id !== Auth::id() ) {
            return redirect()->route('cart.index')->with('error', 'Unauthorized action.');
        }

        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart successfully.');
    }
}
