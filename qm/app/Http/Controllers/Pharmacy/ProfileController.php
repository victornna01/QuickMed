<?php
namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit() { return view('pharmacy.profile', ['pharmacy' => auth()->user()->pharmacy]); }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150', 'address' => 'required|string|max:255', 'area' => 'required|string|max:100',
            'phone' => 'required|string|max:30', 'email' => 'nullable|email|max:150',
            'opening_hours' => 'nullable|string|max:150', 'description' => 'nullable|string|max:1000',
        ]);
        auth()->user()->pharmacy->update($data);
        return back()->with('success', 'Pharmacy profile updated.');
    }
}
