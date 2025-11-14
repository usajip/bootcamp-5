<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // Admin Transaction List
    public function index()
    {
        $transactions = Transaction::with('user')
                            ->withCount('details');

        if(Auth::user()->role !== 'admin'){
            $transactions = $transactions->where('user_id', Auth::id());
        }

        $transactions = $transactions->paginate(10);
        return view('admin.transaction.index', compact('transactions'));
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
        // Validate the request data
        $request->validate([
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'payment_method'=>'required|string|max:50',
        ]);

        $total_amount = Cart::where('user_id', Auth::id())
                        ->get()
                        ->sum(function($item) {
                            return $item->product->price * $item->quantity;
                        });

        // Create a new transaction
        $transaction = new Transaction();
        $transaction->user_id = Auth::id();
        $transaction->phone_number = $request->phone;
        $transaction->address = $request->address;
        $transaction->payment_method = $request->payment_method;
        $transaction->status = 'pending';
        $transaction->total = $total_amount;
        $transaction->save();

        //transaction detail
        $carts = Cart::where('user_id', Auth::id())->get();
        foreach($carts as $cart){
            TransactionDetail::create([
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->product->price,
                'transaction_id' => $transaction->id,
            ]);
        }

        // Clear the user's cart
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('transaction.show', $transaction->id)->with('success', 'Transaction completed successfully!');
    }

    /**
     * Display the specified resource.
     */
    // Tranaction success page
    public function show(Transaction $transaction)
    {
        // show transaction success page
        return view('transaction_success', compact('transaction'));
    }

    // Display Transaction Detail for Admin
    public function detail($id)
    {
        $transaction = Transaction::with([
                                'details.product',
                                'user'
                            ])
                            ->findOrFail($id);
        return view('admin.transaction.detail', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|string|in:pending,completed,failed',
        ]);

        $transaction->status = $request->status;
        $transaction->save();

        return redirect()->back()->with('success', 'Transaction status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->details()->delete();
        
        $transaction->delete();
        return redirect()->back()->with('success', 'Transaction deleted successfully.');
    }
}
