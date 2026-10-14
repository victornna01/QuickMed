<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    private function rules(?Drug $drug = null): array
    {
        return ['name' => 'required|string|max:150|unique:drugs,name' . ($drug ? ",{$drug->id}" : ''),
                'generic_name' => 'nullable|string|max:150', 'category' => 'nullable|string|max:100', 'description' => 'nullable|string|max:1000'];
    }

    public function index(Request $request)
    {
        $drugs = Drug::withCount('listings')
            ->when($request->q, fn ($q, $s) => $q->where('name', 'like', "%{$s}%")->orWhere('category', 'like', "%{$s}%"))
            ->orderBy('name')->paginate(12)->withQueryString();
        return view('admin.drugs.index', compact('drugs'));
    }

    public function create() { return view('admin.drugs.form', ['drug' => new Drug]); }

    public function store(Request $request)
    {
        Drug::create($request->validate($this->rules()));
        return redirect()->route('admin.drugs.index')->with('success', 'Medicine added.');
    }

    public function edit(Drug $drug) { return view('admin.drugs.form', compact('drug')); }

    public function update(Request $request, Drug $drug)
    {
        $drug->update($request->validate($this->rules($drug)));
        return redirect()->route('admin.drugs.index')->with('success', 'Medicine updated.');
    }

    public function destroy(Drug $drug)
    {
        $drug->delete();
        return back()->with('success', 'Medicine deleted.');
    }
}
