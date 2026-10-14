<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['drug', 'pharmacy', 'user'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(12)->withQueryString();
        return view('admin.orders', compact('orders'));
    }
}
