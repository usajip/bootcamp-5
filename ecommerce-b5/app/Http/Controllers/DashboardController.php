<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $total_product = Product::count();
        $total_stock = Product::sum('stock');
        $total_category = ProductCategory::count();
        $total_clicks = 105;

        // $last_month_transaction  = Transaction::where('created_at', '>=', now()->subMonth())
        // ->where('created_at', '<', now()->startOfMonth())
        // ->where('status', 'completed')
        // ->sum('amount');

        // $current_month_transaction  = Transaction::where('created_at', '>=', now()->startOfMonth())
        // ->where('status', 'completed')
        // ->sum('amount');

        $data_dashboard = [
            'total_product' => [
                'icon'=>'box',
                'color'=>'#f59e0b',
                'value'=>$total_product
            ],
            'total_stock' => [
                'icon'=>'inventory_2',
                'color'=> '#ef4444',
                'value'=>$total_stock
            ],
            'total_category' => [
                'icon'=>'ads_click',
                'color'=>'#10b981',
                'value'=>$total_category
            ],
            'total_clicks' => [
                'icon'=>'visibility',
                'color'=>'#3b82f6',
                'value'=>$total_clicks
            ],
        ];

        return view('dashboard', compact('data_dashboard'));
    }
}
