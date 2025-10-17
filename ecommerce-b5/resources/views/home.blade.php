@extends('template.layout')
@section('title', $title ?? 'Home')
@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Our Products</h2>
        <div class="row">
            @foreach($products as $item)
                <div class="col-md-4 mb-4">
                    <x-product-card 
                        :name="$item['name']" 
                        :description="$item['description']" 
                        :price="$item['price']" 
                        :image="$item['image']" 
                        :id="$item['id']" />
                </div>
            @endforeach
        </div>
        @if(empty($products))
            <x-alert type="info">No products available.</x-alert>
        @endif
    </div>
@push('styles')
    <style>
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
@endpush
@push('scripts')
    <script>
        // Custom JavaScript can be added here
    </script>
@endpush
@endsection