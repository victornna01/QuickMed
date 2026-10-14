<?php
namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\{Order, PharmacyDrug};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = auth()->user()->pharmacy->orders()->with(['drug', 'user'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(10)->withQueryString();
        return view('pharmacy.orders', compact('orders'));
    }

    public function update(Request $request, Order $order)
    {
        abort_unless($order->pharmacy_id === auth()->user()->pharmacy->id, 403);
        $status = $request->validate(['status' => 'required|string'])['status'];
        if (! in_array($status, $order->nextStatuses(), true)) {
            return back()->with('error', 'That status change is not allowed.');
        }
        DB::transaction(function () use ($order, $status) {
            $order->update(['status' => $status]);
            if ($status === 'rejected') { // return stock
                PharmacyDrug::where('pharmacy_id', $order->pharmacy_id)->where('drug_id', $order->drug_id)->increment('quantity', $order->quantity);
            }
        });
        return back()->with('success', 'Order marked as ' . Order::STATUSES[$status] . '.');
    }
}
