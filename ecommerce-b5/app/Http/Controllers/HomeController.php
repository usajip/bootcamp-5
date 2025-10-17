<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $title = "Home Page";
        $phone_number = "081234567890";
        $products = [
                    [
                        'id' => 1,
                        'name' => 'Product One',
                        'description' => 'This is a description for product one. It is a great product with many features.',
                        'price' => 150000,
                        'image' => 'https://eduwork.id/images/privatenew/thumbnail/mern.webp',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Product Two',
                        'description' => 'Product two is even better and comes with extra accessories.',
                        'price' => 250000,
                        'image' => 'https://eduwork.id/images/privatenew/thumbnail/mern.webp',
                    ],
                    [
                        'id' => 3,
                        'name' => 'Product Three',
                        'description' => 'The third product in our lineup, known for its reliability.',
                        'price' => 350000,
                        'image' => 'https://eduwork.id/images/privatenew/thumbnail/mern.webp',
                    ],
                ];
        return view('home', compact('products', 'title', 'phone_number'));
    }

    public function detailProduct($id)
    {
        $product = [
                        'id' => $id,
                        'name' => 'Product One',
                        'description' => 'This is a description for product one. It is a great product with many features.',
                        'price' => 150000,
                        'image' => 'https://eduwork.id/images/privatenew/thumbnail/mern.webp',
                    ];
        return view('product', compact('product'));
    }

    public function cart()
    {
        return view('cart');
    }

    public function checkout()
    {
        return view('checkout');
    }
}
