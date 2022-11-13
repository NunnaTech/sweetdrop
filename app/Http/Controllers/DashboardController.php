<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Store;
use App\Models\User;

class DashboardController extends Controller
{
    public function admin()
    {
        $stores = Store::query()->where('is_active', true)->count();
        $orders = Order::query()
            ->where('is_completed', '=', true)
            ->where('is_active', '=', true)
            ->count();
        $dealers = User::where('role_id', 2)->where('is_active', true)->count();
        $products = Product::query()->where('is_active', true)->count();
        $sales = Sale::query()->sum('total');
        $lastOrders = Order::query()
            ->where('is_completed', '=', true)
            ->where('is_active', '=', true)
            ->orderBy('deliver_date', 'desc')
            ->take(10)
            ->with('status', 'store')
            ->get();

        return [
            'success' => true,
            'message' => 'Dashboard admin',
            'data' => [
                'stores' => $stores,
                'orders' => $orders,
                'dealers' => $dealers,
                'products' => $products,
                'sales' => $sales,
                'lastOrders' => $lastOrders,
            ]
        ];
    }

    public function dealer()
    {
        $ownStores = User::with('dealers')
            ->where('id', '=', 3)
            ->first()->dealers
            ->count();
        $ownOrders = Order::query()
            ->where('delivered_by', '=', 3)
            ->where('is_completed', '=', true)
            ->where('is_active', '=', true)
            ->count();
        $ownLastOrders = Order::query()
            ->where('delivered_by', '=', 3)
            ->where('is_completed', '=', true)
            ->where('is_active', '=', true)
            ->orderBy('deliver_date', 'desc')
            ->take(10)
            ->with('status', 'store')
            ->get();
        return [
            'success' => true,
            'message' => 'Dashboard delader',
            'data' => [
                'ownStores' => $ownStores,
                'ownOrders' => $ownOrders,
                'ownLastOrders' => $ownLastOrders
            ]
        ];

    }
}
