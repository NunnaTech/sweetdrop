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
            ->where('status_id', '=', 3)
            ->count();
        $dealers = User::where('role_id', 2)->where('is_active', true)->count();
        $products = Product::query()->where('is_active', true)->count();
        $sales = Sale::query()->sum('total');
        $lastOrders = Order::query()
            ->where('is_completed', '=', true)
            ->where('is_active', '=', true)
            ->where('status_id', '=', 3)
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
        $user = auth()->user();
        $ownStores = User::with('dealers')
            ->where('id', '=', $user->id)
            ->first()->dealers
            ->count();
        $ownOrders = Order::query()
            ->where('delivered_by', '=', $user->id)
            ->where('is_completed', '=', true)
            ->where('is_active', '=', true)
            ->where('status_id', '=', 3)
            ->count();
        $ownLastOrders = Order::query()
            ->where('delivered_by', '=', $user->id)
            ->where('is_completed', '=', true)
            ->where('is_active', '=', true)
            ->where('status_id', '=', 3)
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
