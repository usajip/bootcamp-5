@extends('template.layout')
@section('title', $title ?? 'Home')
@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Shopping Cart</h2>
        <x-alert type="info">Your cart is currently empty.</x-alert>
    </div>
@endsection