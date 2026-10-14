@extends('layouts.app')
@section('title', 'My Orders')
@section('content')
@php $cur = env('APP_CURRENCY', '₦'); @endphp
<h2 class="mb-3">My orders</h2>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
  <thead class="table-light"><tr><th>#</th><th>Medicine</th><th>Pharmacy</th><th>Qty</th><th>Total</th><th>Type</th><th>Status</th><th>Date</th><th></th></tr></thead>
  <tbody>
  @forelse($orders as $o)
    <tr>
      <td>{{ $o->id }}</td><td>{{ $o->drug->name }}</td><td>{{ $o->pharmacy->name }}</td><td>{{ $o->quantity }}</td>
      <td>{{ $cur }}{{ number_format($o->total, 2) }}</td><td class="text-capitalize">{{ $o->fulfilment }}</td>
      <td><span class="badge text-bg-{{ $o->status_class }}">{{ $o->status_label }}</span></td><td class="small">{{ $o->created_at->format('d M Y, H:i') }}</td>
      <td>@if($o->status === 'pending')<form method="POST" action="{{ route('orders.cancel', $o) }}" onsubmit="return confirm('Cancel this order?')">@csrf @method('PATCH')<button class="btn btn-outline-danger btn-sm">Cancel</button></form>@endif</td>
    </tr>
  @empty<tr><td colspan="9" class="text-center text-muted py-4">You have not placed any orders yet. <a href="{{ route('search') }}">Find a medicine</a></td></tr>@endforelse
  </tbody></table></div></div>
<div class="mt-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
@endsection
