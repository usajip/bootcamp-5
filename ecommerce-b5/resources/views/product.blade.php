@extends('template.layout')
@section('title', 'Product Page')
@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Product Details</h2>
        <div class="row">
            <div class="col-md-6">
                @if($product['image'])
                    <img src="{{ $product['image'] }}" class="img-fluid" alt="{{ $product['name'] }}">
                @else
                    <img src="https://via.placeholder.com/600x400?text=No+Image" class="img-fluid" alt="No Image">
                @endif
            </div>
            <div class="col-md-6">
                <h3>{{ $product['name'] }}</h3>
                <p>{{ $product['description'] }}</p>
                <p><strong>Price:</strong> Rp{{ number_format($product['price'], 0, ',', '.') }}</p>
                <a href="{{ route('cart') }}" class="btn btn-success">Add to Cart</a>
            </div>
        </div>
    </div>
@endsection