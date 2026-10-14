@extends('layouts.app')
@section('title', 'Orders')
@section('content')
@php $cur = env('APP_CURRENCY', '₦'); @endphp
@include('admin._nav')
<div class="d-flex flex-wrap gap-2 mb-3">
  <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ request('status') ? 'btn-outline-secondary' : 'btn-primary' }}">All</a>
  @foreach(\App\Models\Order::STATUSES as $k => $l)<a href="{{ route('admin.orders.index', ['status' => $k]) }}" class="btn btn-sm {{ request('status') === $k ? 'btn-primary' : 'btn-outline-secondary' }}">{{ $l }}</a>@endforeach
</div>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
  <thead class="table-light"><tr><th>#</th><th>Customer</th><th>Pharmacy</th><th>Medicine</th><th>Qty</th><th>Total</th><th>Type</th><th>Status</th><th>Date</th></tr></thead><tbody>
  @forelse($orders as $o)
    <tr><td>{{ $o->id }}</td><td>{{ $o->user->name }}</td><td>{{ $o->pharmacy->name }}</td><td>{{ $o->drug->name }}</td><td>{{ $o->quantity }}</td><td>{{ $cur }}{{ number_format($o->total, 2) }}</td>
      <td class="text-capitalize">{{ $o->fulfilment }}</td><td><span class="badge text-bg-{{ $o->status_class }}">{{ $o->status_label }}</span></td><td class="small">{{ $o->created_at->format('d M Y') }}</td></tr>
  @empty<tr><td colspan="9" class="text-center text-muted py-4">No orders found.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
@endsection
