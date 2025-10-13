<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('user/{id}/{name}', function ($id, $name) {
    return "User ID: " . $id . ", Name: " . $name;
});

Route::post('/submit', function () {
    return "Form Submitted";
});

Route::put('/update', function () {
    return "Resource Updated";
});

Route::delete('/delete', function () {
    return "Resource Deleted";
});

