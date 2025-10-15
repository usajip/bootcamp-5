@extends('template.layout')
@section('title', 'Home Page')
@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Our Products</h2>
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($product['image'])
                            <img src="{{ $product['image'] }}" class="card-img-top" alt="{{ $product['name'] }}">
                        @else
                            <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="No Image">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product['name'] }}</h5>
                            <p class="card-text">{{ \Illuminate\Support\Str::limit($product['description'], 80) }}</p>
                            <p class="card-text"><strong>Price:</strong> Rp{{ number_format($product['price'], 0, ',', '.') }}</p>
                            <a href="#!" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if(empty($products))
            <div class="alert alert-info">No products available.</div>
        @endif
    </div>
@endsection