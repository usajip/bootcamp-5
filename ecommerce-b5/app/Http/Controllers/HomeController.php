<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $product = Product::with('category')->findOrFail($id);
        $recommendations = Product::where('product_category_id', $product->product_category_id)
                            ->where('id', '!=', $product->id)
                            ->orderBy('created_at', 'desc')
                            ->take(4)
                            ->get();

        $is_not_admin = true;

        if(Auth::check()){
            $is_not_admin = Auth::user()->role !== 'admin';
        }
        
        if($is_not_admin){
            // Track product clicks using session
            $clickedProducts = session()->get('clicked_products', []);
            if (!in_array($id, $clickedProducts)) {

                // Increment click count in database
                Product::where('id', $id)->increment('click');
                
                // Add to session to prevent multiple increments in same session
                $clickedProducts[] = $id;
                session()->put('clicked_products', $clickedProducts);
            }
        }

        return view('product', compact('product', 'recommendations'));
    }
}
