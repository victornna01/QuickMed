@extends('layouts.app')
@section('title', 'Find Medicine')
@section('content')
@php $cur = env('APP_CURRENCY', '₦'); @endphp
<h2 class="mb-3">Find a medicine</h2>
<form class="card p-3 mb-4"><div class="row g-2 align-items-end">
  <div class="col-md-4"><label class="form-label small">Medicine</label><input name="q" value="{{ $q }}" class="form-control" placeholder="Name, generic name or category" required></div>
  <div class="col-md-3"><label class="form-label small">Area</label><input name="area" value="{{ $area }}" class="form-control" placeholder="e.g. Wuse"></div>
  <div class="col-md-2"><label class="form-label small">Sort by price</label><select name="sort" class="form-select"><option value="price_asc" @selected($sort==='price_asc')>Lowest first</option><option value="price_desc" @selected($sort==='price_desc')>Highest first</option></select></div>
  <div class="col-md-2"><div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="in_stock" value="1" id="is" @checked($inStock)><label class="form-check-label small" for="is">In stock only</label></div></div>
  <div class="col-md-1 d-grid"><button class="btn btn-primary"><i class="bi bi-search"></i></button></div>
</div></form>

@if($q === '')
  <div class="text-center text-muted py-5"><i class="bi bi-search fs-1"></i><p class="mt-2">Type a medicine name to see which pharmacies have it listed.</p></div>
@elseif($drugs->isEmpty())
  <div class="alert alert-warning">No pharmacies found with "{{ $q }}" listed{{ $area ? ' in "'.$area.'"' : '' }}. Try a different spelling, remove the area, or untick "In stock only".</div>
@else
  @foreach($drugs as $drug)
    <div class="card mb-4">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0">{{ $drug->name }}</h5>
        <div class="small text-muted">{{ $drug->generic_name }} @if($drug->category) &middot; {{ $drug->category }} @endif &middot; {{ $drug->listings->count() }} pharmacies</div>
      </div>
      <div class="table-responsive"><table class="table align-middle mb-0">
        <thead class="table-light"><tr><th>Pharmacy</th><th>Location</th><th>Price</th><th>Availability</th><th></th></tr></thead>
        <tbody>
        @foreach($drug->listings as $l)
          <tr>
            <td><a href="{{ route('pharmacies.show', $l->pharmacy) }}" class="fw-semibold text-decoration-none">{{ $l->pharmacy->name }}</a> <i class="bi bi-patch-check-fill text-primary small"></i><div class="small text-muted">{{ $l->pharmacy->phone }}</div></td>
            <td class="small">{{ $l->pharmacy->address }}</td>
            <td class="fw-semibold">{{ $cur }}{{ number_format($l->price, 2) }}</td>
            <td><span class="badge text-bg-{{ $l->availability_class }}">{{ $l->availability_label }}</span></td>
            <td class="text-end">
              @if($l->canOrder())<a href="{{ route('orders.create', $l) }}" class="btn btn-primary btn-sm">Order / Delivery</a>@else<button class="btn btn-outline-secondary btn-sm" disabled>Unavailable</button>@endif
            </td>
          </tr>
        @endforeach
        </tbody></table></div>
    </div>
  @endforeach
@endif
<p class="small text-muted">Prices and availability are entered by pharmacies and may change. Confirm with the pharmacy.</p>
@endsection
