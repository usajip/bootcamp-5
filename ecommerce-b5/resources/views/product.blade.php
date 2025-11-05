@extends('template.layout')
@section('title', 'Product Page')
@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Product Details</h2>
        <div class="row">
            <div class="col-md-6">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" class="img-fluid" style="max-height: 300px" alt="{{ $product->name }}">
                @else
                    <img src="https://via.placeholder.com/600x400?text=No+Image" class="img-fluid" alt="No Image">
                @endif
            </div>
            <div class="col-md-6">
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
                <p><strong>Price:</strong> Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                <a href="{{ route('cart') }}" class="btn btn-success">Add to Cart</a>
            </div>
        </div>
        <hr>
        {{-- Product Recommendations --}}
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-4">Product Lainnya</h2>
            <a href="{{ route('home').'?category='.$product->category->id }}" class="btn btn-secondary">View All</a>
        </div>
        <div class="row">
            @foreach($recommendations as $item)
                <div class="col-md-3 mb-4">
                    <x-product-card 
                        :category="$item->category->name ?? 'Uncategorized'"
                        :name="$item->name" 
                        :description="$item->description" 
                        :price="$item->price" 
                        :image="asset($item->image)" 
                        :id="$item->id" />
                </div>
            @endforeach
        </div>
    </div>
@endsection