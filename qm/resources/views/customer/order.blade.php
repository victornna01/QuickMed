@extends('layouts.app')
@section('title', 'Order medicine')
@section('content')
@php $cur = env('APP_CURRENCY', '₦'); @endphp
<div class="row justify-content-center"><div class="col-lg-7">
  <div class="card p-4">
    <h3>Order / delivery request</h3>
    <div class="p-3 rounded mb-3" style="background:var(--qm-soft)">
      <b>{{ $listing->drug->name }}</b> from <b>{{ $listing->pharmacy->name }}</b><br>
      <span class="small text-muted">{{ $listing->pharmacy->address }}</span><br>
      Price: <b>{{ $cur }}{{ number_format($listing->price, 2) }}</b> each &middot; <span class="badge text-bg-{{ $listing->availability_class }}">{{ $listing->quantity }} available</span>
    </div>
    <form method="POST" action="{{ route('orders.store', $listing) }}">@csrf
      <div class="row g-3">
        <div class="col-md-4"><label class="form-label">Quantity</label><input type="number" name="quantity" min="1" max="{{ $listing->quantity }}" value="{{ old('quantity', 1) }}" class="form-control" required></div>
        <div class="col-md-8"><label class="form-label">Phone number</label><input name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="form-control" required></div>
        <div class="col-12"><label class="form-label d-block">How do you want to get it?</label>
          <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="fulfilment" id="f1" value="pickup" @checked(old('fulfilment','pickup')==='pickup')><label class="form-check-label" for="f1">I will pick up</label></div>
          <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="fulfilment" id="f2" value="delivery" @checked(old('fulfilment')==='delivery')><label class="form-check-label" for="f2">Request delivery</label></div></div>
        <div class="col-12" id="addr" style="display:none"><label class="form-label">Delivery address</label><textarea name="delivery_address" rows="2" class="form-control">{{ old('delivery_address', auth()->user()->address) }}</textarea></div>
        <div class="col-12"><label class="form-label">Note to pharmacy (optional)</label><textarea name="note" rows="2" class="form-control">{{ old('note') }}</textarea></div>
      </div>
      <div class="mt-4 d-flex gap-2"><button class="btn btn-primary">Send request</button><a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Back</a></div>
      <p class="small text-muted mt-3 mb-0">The pharmacy will confirm your request. Delivery fees, if any, are agreed with the pharmacy.</p>
    </form>
  </div>
</div></div>
@push('scripts')
<script>
const a = document.getElementById('addr');
const sync = () => { a.style.display = document.getElementById('f2').checked ? 'block' : 'none'; };
document.querySelectorAll('[name=fulfilment]').forEach(r => r.addEventListener('change', sync)); sync();
</script>
@endpush
@endsection
