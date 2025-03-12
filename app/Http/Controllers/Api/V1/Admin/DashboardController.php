<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // products statistics
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalUsers = Category::count();
        $usersThisMonth = User::whereMonth('created_at',Carbon::now()->month)
                        ->whereYear('created_at',Carbon::now()->year)
                        ->count();
        return response()->json([
            'status' => 'success',
            'data' => [
                'totalProducts' => $totalProducts,
                'totalCategories' => $totalCategories,
                'totalUsers' => $totalUsers,
                'usersThisMonth' => $usersThisMonth,
            ]
        ]);
    }
}
