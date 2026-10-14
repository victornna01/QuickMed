@extends('layouts.app')
@section('title', 'Orders')
@section('content')
@php $cur = env('APP_CURRENCY', '₦'); @endphp
@include('pharmacy._nav')
<div class="d-flex flex-wrap gap-2 mb-3 small">
  <a href="{{ route('pharmacy.orders.index') }}" class="btn btn-sm {{ request('status') ? 'btn-outline-secondary' : 'btn-primary' }}">All</a>
  @foreach(\App\Models\Order::STATUSES as $k => $l)<a href="{{ route('pharmacy.orders.index', ['status' => $k]) }}" class="btn btn-sm {{ request('status') === $k ? 'btn-primary' : 'btn-outline-secondary' }}">{{ $l }}</a>@endforeach
</div>
@forelse($orders as $o)
  <div class="card p-3 mb-3">
    <div class="d-flex flex-wrap justify-content-between gap-2">
      <div><b>#{{ $o->id }} &middot; {{ $o->drug->name }}</b> &times; {{ $o->quantity }} &middot; {{ $cur }}{{ number_format($o->total, 2) }}
        <div class="small text-muted">{{ $o->user->name }} &middot; {{ $o->phone }} &middot; {{ $o->created_at->format('d M Y, H:i') }}</div></div>
      <div><span class="badge text-bg-{{ $o->status_class }}">{{ $o->status_label }}</span> <span class="badge text-bg-light text-capitalize">{{ $o->fulfilment }}</span></div>
    </div>
    @if($o->fulfilment === 'delivery')<div class="small mt-2"><i class="bi bi-truck"></i> {{ $o->delivery_address }}</div>@endif
    @if($o->note)<div class="small mt-1 text-muted">Note: {{ $o->note }}</div>@endif
    @if($o->nextStatuses())
      <div class="mt-3 d-flex flex-wrap gap-2">
        @foreach($o->nextStatuses() as $next)
          <form method="POST" action="{{ route('pharmacy.orders.update', $o) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="{{ $next }}">
            <button class="btn btn-sm {{ $next === 'rejected' ? 'btn-outline-danger' : 'btn-primary' }}">{{ ['confirmed'=>'Confirm','rejected'=>'Reject','out_for_delivery'=>'Out for delivery','completed'=>'Mark completed'][$next] }}</button></form>
        @endforeach
      </div>
    @endif
  </div>
@empty<p class="text-center text-muted py-4">No orders found.</p>@endforelse
{{ $orders->links('pagination::bootstrap-5') }}
@endsection
