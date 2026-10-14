<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function index(Request $request)
    {
        $pharmacies = Pharmacy::with('owner')->withCount('listings')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(10)->withQueryString();
        return view('admin.pharmacies', compact('pharmacies'));
    }

    public function update(Request $request, Pharmacy $pharmacy)
    {
        $data = $request->validate(['status' => 'required|in:pending,verified,rejected']);
        $pharmacy->update($data);
        return back()->with('success', "{$pharmacy->name} marked as {$data['status']}.");
    }
}
