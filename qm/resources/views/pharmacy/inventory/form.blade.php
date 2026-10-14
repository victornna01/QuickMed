@extends('layouts.app')
@section('title', 'Medicine')
@section('content')
@include('pharmacy._nav')
<div class="card p-4"><h4 class="mb-3">{{ $listing->exists ? 'Edit ' . $listing->drug->name : 'Add medicine to inventory' }}</h4>
<form method="POST" action="{{ $listing->exists ? route('pharmacy.inventory.update', $listing) : route('pharmacy.inventory.store') }}">@csrf @if($listing->exists) @method('PUT') @endif
  <div class="row g-3">
    @unless($listing->exists)
      <div class="col-md-6"><label class="form-label">Medicine name</label><input name="name" list="drugList" value="{{ old('name') }}" class="form-control" placeholder="Choose existing or type a new one" required>
        <datalist id="drugList">@foreach($drugs as $d)<option value="{{ $d->name }}">@endforeach</datalist></div>
      <div class="col-md-3"><label class="form-label">Generic name</label><input name="generic_name" value="{{ old('generic_name') }}" class="form-control"></div>
      <div class="col-md-3"><label class="form-label">Category</label><input name="category" value="{{ old('category') }}" class="form-control"></div>
      <div class="col-12"><label class="form-label">Description</label><textarea name="description" rows="2" class="form-control">{{ old('description') }}</textarea><div class="form-text">Generic name, category and description are only used if the medicine is new to QuickMed.</div></div>
    @endunless
    <div class="col-md-4"><label class="form-label">Price</label><input type="number" step="0.01" min="0.01" name="price" value="{{ old('price', $listing->price) }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Quantity in stock</label><input type="number" min="0" name="quantity" value="{{ old('quantity', $listing->quantity) }}" class="form-control" required></div>
    <div class="col-md-4 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_available" value="1" id="av" @checked(old('is_available', $listing->is_available))><label class="form-check-label" for="av">Available for sale</label></div></div>
  </div>
  <div class="mt-4 d-flex gap-2"><button class="btn btn-primary">Save</button><a href="{{ route('pharmacy.inventory.index') }}" class="btn btn-outline-secondary">Cancel</a></div>
</form></div>
@endsection
