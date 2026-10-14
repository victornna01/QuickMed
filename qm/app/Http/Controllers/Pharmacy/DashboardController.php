<?php
namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $pharmacy = auth()->user()->pharmacy;
        return view('pharmacy.dashboard', [
            'pharmacy' => $pharmacy,
            'listingCount' => $pharmacy->listings()->count(),
            'lowStock' => $pharmacy->listings()->where('quantity', '<=', 5)->count(),
            'pending' => $pharmacy->orders()->where('status', 'pending')->count(),
            'totalOrders' => $pharmacy->orders()->count(),
            'sales' => (float) $pharmacy->orders()->where('status', 'completed')->sum('total'),
            'recent' => $pharmacy->orders()->with(['drug', 'user'])->latest()->take(5)->get(),
        ]);
    }
}
