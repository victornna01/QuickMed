@extends('layouts.app')
@section('title', $pharmacy->name)
@section('content')
@php $cur = env('APP_CURRENCY', '₦'); @endphp
<div class="card p-4 mb-4">
  <h2>{{ $pharmacy->name }} <i class="bi bi-patch-check-fill text-primary" title="Verified pharmacy"></i></h2>
  <div class="row g-2 text-muted">
    <div class="col-md-6"><i class="bi bi-geo-alt"></i> {{ $pharmacy->address }}</div>
    <div class="col-md-3"><i class="bi bi-telephone"></i> {{ $pharmacy->phone }}</div>
    <div class="col-md-3"><i class="bi bi-clock"></i> {{ $pharmacy->opening_hours ?? 'Hours not provided' }}</div>
  </div>
  @if($pharmacy->description)<p class="mt-3 mb-0">{{ $pharmacy->description }}</p>@endif
</div>
<h4 class="mb-3">Listed medicines</h4>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
  <thead class="table-light"><tr><th>Medicine</th><th>Category</th><th>Price</th><th>Availability</th><th></th></tr></thead>
  <tbody>
  @forelse($listings as $l)
    <tr>
      <td><b>{{ $l->drug->name }}</b><div class="small text-muted">{{ $l->drug->generic_name }}</div></td>
      <td>{{ $l->drug->category }}</td>
      <td>{{ $cur }}{{ number_format($l->price, 2) }}</td>
      <td><span class="badge text-bg-{{ $l->availability_class }}">{{ $l->availability_label }}</span></td>
      <td class="text-end">@if($l->canOrder())<a href="{{ route('orders.create', $l) }}" class="btn btn-primary btn-sm">Order / Delivery</a>@endif</td>
    </tr>
  @empty<tr><td colspan="5" class="text-center text-muted py-4">This pharmacy has not listed any medicines yet.</td></tr>@endforelse
  </tbody></table></div></div>
@endsection
