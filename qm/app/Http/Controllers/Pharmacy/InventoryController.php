<?php
namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\{Drug, PharmacyDrug};
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    private function pharmacy() { return auth()->user()->pharmacy; }

    private function owned(PharmacyDrug $listing): PharmacyDrug
    {
        abort_unless($listing->pharmacy_id === $this->pharmacy()->id, 403);
        return $listing;
    }

    public function index(Request $request)
    {
        $s = trim((string) $request->query('q'));
        $listings = $this->pharmacy()->listings()->with('drug')
            ->when($s !== '', fn ($q) => $q->whereHas('drug', fn ($d) => $d->where('name', 'like', "%{$s}%")))
            ->latest()->paginate(10)->withQueryString();
        return view('pharmacy.inventory.index', compact('listings'));
    }

    public function create()
    {
        return view('pharmacy.inventory.form', ['listing' => new PharmacyDrug(['is_available' => true]), 'drugs' => Drug::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150', 'generic_name' => 'nullable|string|max:150', 'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0.01', 'quantity' => 'required|integer|min:0', 'is_available' => 'nullable|boolean',
        ]);
        $drug = Drug::whereRaw('lower(name) = ?', [mb_strtolower($data['name'])])->first()
            ?? Drug::create(['name' => $data['name'], 'generic_name' => $data['generic_name'] ?? null, 'category' => $data['category'] ?? null, 'description' => $data['description'] ?? null]);

        if ($this->pharmacy()->listings()->where('drug_id', $drug->id)->exists()) {
            return back()->withInput()->withErrors(['name' => 'You have already listed this medicine. Edit the existing entry instead.']);
        }
        $this->pharmacy()->listings()->create(['drug_id' => $drug->id, 'price' => $data['price'], 'quantity' => $data['quantity'], 'is_available' => $request->boolean('is_available')]);
        return redirect()->route('pharmacy.inventory.index')->with('success', 'Medicine added to your inventory.');
    }

    public function edit(PharmacyDrug $listing)
    {
        return view('pharmacy.inventory.form', ['listing' => $this->owned($listing)->load('drug'), 'drugs' => collect()]);
    }

    public function update(Request $request, PharmacyDrug $listing)
    {
        $data = $request->validate(['price' => 'required|numeric|min:0.01', 'quantity' => 'required|integer|min:0']);
        $this->owned($listing)->update($data + ['is_available' => $request->boolean('is_available')]);
        return redirect()->route('pharmacy.inventory.index')->with('success', 'Inventory updated.');
    }

    public function destroy(PharmacyDrug $listing)
    {
        $this->owned($listing)->delete();
        return back()->with('success', 'Medicine removed from your inventory.');
    }
}
