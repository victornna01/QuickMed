@extends('layouts.app')
@section('title', 'Pharmacy Dashboard')
@section('content')
@php $cur = env('APP_CURRENCY', '₦'); @endphp
@include('pharmacy._nav')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
  <div><h2 class="mb-0">{{ $pharmacy->name }}</h2><span class="text-muted small">{{ $pharmacy->address }}</span></div>
  <span class="badge fs-6 text-bg-{{ ['verified'=>'success','pending'=>'warning','rejected'=>'danger'][$pharmacy->status] }}">{{ ucfirst($pharmacy->status) }}</span>
</div>
@if($pharmacy->status !== 'verified')
  <div class="alert alert-warning">{{ $pharmacy->status === 'pending' ? 'Your pharmacy is awaiting verification by an administrator. It is not visible to customers yet, but you can already add your medicines.' : 'Your pharmacy was not verified. Please check your details in Profile and contact the administrator.' }}</div>
@endif
<div class="row g-3 mb-4">
  @foreach([['bi-capsule','Medicines listed',$listingCount],['bi-exclamation-triangle','Low or no stock',$lowStock],['bi-hourglass-split','Pending orders',$pending],['bi-receipt','Total orders',$totalOrders],['bi-cash-coin','Completed sales',$cur.number_format($sales,2)]] as $c)
    <div class="col-6 col-lg"><div class="card p-3 h-100"><div class="stat-icon mb-2"><i class="bi {{ $c[0] }}"></i></div><div class="text-muted small">{{ $c[1] }}</div><div class="fs-5 fw-semibold">{{ $c[2] }}</div></div></div>
  @endforeach
</div>
<div class="card"><div class="card-header bg-white fw-semibold">Recent orders</div><div class="table-responsive"><table class="table mb-0 align-middle">
  <thead class="table-light"><tr><th>#</th><th>Customer</th><th>Medicine</th><th>Qty</th><th>Status</th></tr></thead><tbody>
  @forelse($recent as $o)<tr><td>{{ $o->id }}</td><td>{{ $o->user->name }}</td><td>{{ $o->drug->name }}</td><td>{{ $o->quantity }}</td><td><span class="badge text-bg-{{ $o->status_class }}">{{ $o->status_label }}</span></td></tr>
  @empty<tr><td colspan="5" class="text-center text-muted py-3">No orders yet.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
