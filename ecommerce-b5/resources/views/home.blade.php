@extends('template.layout')
@section('title', $title ?? 'Home')
@section('content')
    <div class="container mt-4">
        {{-- Filter By Category --}}
        <div class="mb-4">
            <h4>Categories</h4>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('home') }}" class="btn {{ request()->category == null ? 'btn-primary' : 'btn-outline-secondary' }}">
                    All Categories
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('home', ['category' => $cat->id]) }}" class="btn {{ request()->category == $cat->id ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ $cat->name }} ({{ $cat->products_count }})
                    </a>
                @endforeach
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Urutkan Berdasarkan
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item {{ request()->orderBy == 'price_asc' ? 'active' : '' }}" href="{{ route('home').'?orderBy=price_asc' }}">Harga: Rendah ke Tinggi</a></li>
                        <li><a class="dropdown-item {{ request()->orderBy == 'price_desc' ? 'active' : '' }}" href="{{ route('home').'?orderBy=price_desc' }}">Harga: Tinggi ke Rendah</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <h2 class="mb-4">Our Products</h2>
        <div class="row">
            @foreach($products as $item)
                <div class="col-md-4 mb-4">
                    <x-product-card 
                        :category="$item->category->name ?? 'Uncategorized'"
                        :name="$item->name" 
                        :description="$item->description" 
                        :price="$item->price" 
                        :image="$item->image" 
                        :id="$item->id" />
                </div>
            @endforeach
            <div class="col-12">
                <div class="d-flex justify-content-center w-100">
                    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
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