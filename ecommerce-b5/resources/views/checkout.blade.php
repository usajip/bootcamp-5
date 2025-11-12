@extends('template.layout')
@section('title', $title ?? 'Home')
@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Checkout</h2>
        {{-- Product List --}}
        <div class="row mb-5">
            <div class="col-12">
                @include('layouts.return_info', ['is_tailwind' => false])
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($carts as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                            <span>{{ $item->product->name }}</span>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                    <td>
                                        {{ $item->quantity }}
                                    </td>
                                    <td>
                                        Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Your cart is empty</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if($carts->count() > 0)
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <h4>Total: Rp{{ number_format($carts->sum(function($item) { return $item->product->price * $item->quantity; }), 0, ',', '.') }}</h4>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <form action="{{ route('transaction.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Shipping Address</label>
                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select class="form-select" id="payment_method" name="payment_method" required>
                    <option value="credit_card">Credit Card</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="e_wallet">E-Wallet</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>
    </div>
@endsection