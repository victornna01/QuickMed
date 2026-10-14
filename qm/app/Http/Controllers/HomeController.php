<?php
namespace App\Http\Controllers;

use App\Models\{Drug, Pharmacy};
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'drugs' => Drug::whereHas('listings.pharmacy', fn ($p) => $p->verified())->orderBy('name')->take(8)->get(),
            'pharmacies' => Pharmacy::verified()->withCount('listings')->latest()->take(6)->get(),
        ]);
    }

    public function search(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $area = trim((string) $request->query('area'));
        $sort = $request->query('sort', 'price_asc');
        $inStock = $request->boolean('in_stock');
        $drugs = collect();

        if ($q !== '') {
            $drugs = Drug::query()
                ->where(fn ($d) => $d->where('name', 'like', "%{$q}%")->orWhere('generic_name', 'like', "%{$q}%")->orWhere('category', 'like', "%{$q}%"))
                ->with(['listings' => function ($l) use ($area, $sort, $inStock) {
                    $l->whereHas('pharmacy', function ($p) use ($area) {
                        $p->verified();
                        if ($area !== '') {
                            $p->where(fn ($x) => $x->where('area', 'like', "%{$area}%")->orWhere('address', 'like', "%{$area}%"));
                        }
                    })->with('pharmacy');
                    if ($inStock) {
                        $l->where('is_available', true)->where('quantity', '>', 0);
                    }
                    $l->orderBy('price', $sort === 'price_desc' ? 'desc' : 'asc');
                }])
                ->orderBy('name')->get()
                ->filter(fn ($d) => $d->listings->isNotEmpty())->values();
        }

        return view('search', compact('drugs', 'q', 'area', 'sort', 'inStock'));
    }

    public function pharmacies(Request $request)
    {
        $s = trim((string) $request->query('q'));
        $pharmacies = Pharmacy::verified()->withCount('listings')
            ->when($s !== '', fn ($q) => $q->where(fn ($x) => $x->where('name', 'like', "%{$s}%")->orWhere('area', 'like', "%{$s}%")->orWhere('address', 'like', "%{$s}%")))
            ->orderBy('name')->paginate(9)->withQueryString();
        return view('pharmacies.index', compact('pharmacies', 's'));
    }

    public function pharmacy(Pharmacy $pharmacy)
    {
        abort_unless($pharmacy->isVerified(), 404);
        $listings = $pharmacy->listings()->with('drug')->get()->sortBy('drug.name');
        return view('pharmacies.show', compact('pharmacy', 'listings'));
    }
}
