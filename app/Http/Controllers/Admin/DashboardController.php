<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // products statistics
        $totalProducts = Product::count();
        return response()->json([
            'status' => 'success',
            'data' => [
                'totalProducts' => $totalProducts,

            ]
        ]);
    }
}
