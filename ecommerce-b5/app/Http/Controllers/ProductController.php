<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')
                        ->orderBy('id', 'desc')
                        ->paginate(5); //all(), get(), paginate()
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ProductCategory::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'image'=> 'required|image|mimes:jpg,jpeg,png,webp|max:1024',
            'product_category_id' => 'required|exists:product_categories,id',
        ]);

        $imagePath = $request->file('image')->store('products', 'images');
        
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => 'images/'.$imagePath,
            'product_category_id' => $request->product_category_id,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        return view('admin.products.edit', compact('categories', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'image'=> 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'product_category_id' => 'required|exists:product_categories,id',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image 
                && 
                Storage::disk('images')->exists(str_replace('images/', '', $product->image))) {
                Storage::disk('images')->delete(str_replace('images/', '', $product->image));
            }
            // Upload new image
            $imagePath = $request->file('image')->store('products', 'images');
            $product->image = 'images/'.$imagePath;
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->product_category_id = $request->product_category_id;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Product <b>' . $product->name . '</b> updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image 
            && 
            Storage::disk('images')->exists(str_replace('images/', '', $product->image))) {
            Storage::disk('images')->delete(str_replace('images/', '', $product->image));
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
