<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories   = ProductCategory::withCount('products')
                        ->withSum('products', 'stock')
                        ->withSum('products', 'price')
                        ->get();
        $search     = $request->query('search');
        $category   = $request->query('category');
        $orderBy    = $request->query('orderBy');
        $orderByDirection = 'asc';
        if(!in_array($orderBy, ['price_asc', 'price_desc'])) {
            $orderBy = null;
        }else{
            $orderByParts = explode('_', $orderBy);
            $orderByField = $orderByParts[0];
            $orderByDirection = $orderByParts[1];
        }
        $products   = Product::when($search, function ($query, $search) {
                            return $query->where('name', 'like', "%{$search}%")
                                   ->orWhere('description', 'like', "%{$search}%")
                                   ->orWhereHas('category', function ($q) use ($search) {
                                       $q->where('name', 'like', "%{$search}%");
                                   });
                        })
                        ->when($category, function ($query, $category) {
                            return $query->whereHas('category', function ($q) use ($category) {
                                $q->where('id', $category);
                            });
                        })
                        ->with('category')
                        ->orderBy('price', $orderByDirection)
                        ->paginate(6)
                        ->appends($request->all());
        return view('home', compact('products', 'categories', 'search', 'category'));
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
