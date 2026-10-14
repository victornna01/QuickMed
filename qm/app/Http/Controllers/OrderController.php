<?php
namespace App\Http\Controllers;

use App\Models\{Order, PharmacyDrug};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function create(PharmacyDrug $listing)
    {
        $listing->load(['drug', 'pharmacy']);
        abort_unless($listing->pharmacy->isVerified(), 404);
        if (! $listing->canOrder()) {
            return redirect()->route('pharmacies.show', $listing->pharmacy)->with('error', 'This medicine is currently unavailable.');
        }
        return view('customer.order', compact('listing'));
    }

    public function store(Request $request, PharmacyDrug $listing)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
            'fulfilment' => 'required|in:pickup,delivery',
            'delivery_address' => 'required_if:fulfilment,delivery|nullable|string|max:500',
            'phone' => 'required|string|max:30',
            'note' => 'nullable|string|max:500',
        ]);

        $order = DB::transaction(function () use ($listing, $data, $request) {
            $row = PharmacyDrug::whereKey($listing->id)->lockForUpdate()->first();
            abort_unless($row->pharmacy->isVerified(), 404);
            if (! $row->canOrder() || $row->quantity < $data['quantity']) {
                return null;
            }
            $row->decrement('quantity', $data['quantity']);
            return Order::create([
                'user_id' => $request->user()->id, 'pharmacy_id' => $row->pharmacy_id, 'drug_id' => $row->drug_id,
                'quantity' => $data['quantity'], 'unit_price' => $row->price, 'total' => $row->price * $data['quantity'],
                'fulfilment' => $data['fulfilment'], 'delivery_address' => $data['fulfilment'] === 'delivery' ? $data['delivery_address'] : null,
                'phone' => $data['phone'], 'note' => $data['note'] ?? null, 'status' => 'pending',
            ]);
        });

        if (! $order) {
            return back()->withInput()->withErrors(['quantity' => 'Not enough stock available for that quantity.']);
        }
        return redirect()->route('orders.index')->with('success', 'Your request has been sent to the pharmacy.');
    }

    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with(['drug', 'pharmacy'])->latest()->paginate(10);
        return view('customer.orders', compact('orders'));
    }

    public function cancel(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }
        DB::transaction(function () use ($order) {
            $order->update(['status' => 'cancelled']);
            PharmacyDrug::where('pharmacy_id', $order->pharmacy_id)->where('drug_id', $order->drug_id)->increment('quantity', $order->quantity);
        });
        return back()->with('success', 'Order cancelled.');
    }
}
