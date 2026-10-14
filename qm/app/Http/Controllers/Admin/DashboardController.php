<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Drug, Order, Pharmacy, User};

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'users' => User::count(),
            'customers' => User::where('role', 'customer')->count(),
            'verified' => Pharmacy::where('status', 'verified')->count(),
            'pending' => Pharmacy::where('status', 'pending')->count(),
            'drugs' => Drug::count(),
            'orders' => Order::count(),
            'statusCounts' => Order::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status'),
            'pendingPharmacies' => Pharmacy::with('owner')->where('status', 'pending')->latest()->take(5)->get(),
            'recentOrders' => Order::with(['drug', 'pharmacy', 'user'])->latest()->take(6)->get(),
        ]);
    }
}
