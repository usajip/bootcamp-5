<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $total_product = Product::count();
        $total_stock = Product::sum('stock');
        $total_category = ProductCategory::count();
        $total_clicks = Product::sum('click');

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

        // Daily Transaction Chart Data 7 days
        $chart_data = Transaction::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');

            $dayData = $chart_data->firstWhere('date', $date);
            $salesData[] = $dayData ? (float) $dayData->total : 0;
        }

        return view('dashboard', compact('data_dashboard', 'labels', 'salesData'));
    }
}
