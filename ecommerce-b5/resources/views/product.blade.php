@extends('template.layout')
@section('title', 'Product Page')
@section('content')
    <h1>Ini adalah halaman product</h1>
    @push('styles')
        <style>
            h1 {
                color: blue;
            }
        </style>
    @endpush
    <ul>
        @foreach($products as $product)
            <li>{{ $product }}</li>
        @endforeach
    </ul>

@push('styles')
    <style>
        h1 {
            color: blue;
        }
    </style>
@endpush
@push('scripts')
    <script>
        console.log('Product Page Loaded');
    </script>
@endpush
@endsection