@extends('template.layout')
@section('title', $title ?? 'Home')
@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Transaction Successful</h2>
        @include('layouts.return_info', ['is_tailwind' => false])
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Transaction Details</h5>
                <p><strong>Transaction ID:</strong> {{ $transaction->id }}</p>
                <p><strong>Date:</strong> {{ $transaction->created_at->format('d M Y, H:i') }}</p>
                <p><strong>Total Amount:</strong> Rp{{ number_format($transaction->total, 0, ',', '.') }}</p>
                <p><strong>Status:</strong> {{ $transaction->status }}</p>
                <hr>
                <h6>Items Purchased:</h6>
                <ul class="list-group">
                    @foreach($transaction->details as $detail)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                {{ $detail->product->name }} (x{{ $detail->quantity }})
                            </div>
                            <span>Rp{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
                {{-- Chat Admin Button --}}
                <div class="mt-4">
                    <a href="https://wa.me/6281234567890?text=Hello%20Admin,%20I%20have%20completed%20my%20transaction%20with%20ID%20{{ $transaction->id }}." target="_blank" class="btn btn-success">
                        <i class="fab fa-whatsapp"></i> Chat Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection