@extends('layouts.app')
@section('title', 'Pharmacy Profile')
@section('content')
@include('pharmacy._nav')
<div class="card p-4"><h4 class="mb-3">Pharmacy profile</h4>
<form method="POST" action="{{ route('pharmacy.profile.update') }}">@csrf @method('PUT')
  <div class="row g-3">
    <div class="col-md-6"><label class="form-label">Pharmacy name</label><input name="name" value="{{ old('name', $pharmacy->name) }}" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Area / district</label><input name="area" value="{{ old('area', $pharmacy->area) }}" class="form-control" required></div>
    <div class="col-12"><label class="form-label">Address</label><input name="address" value="{{ old('address', $pharmacy->address) }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Phone</label><input name="phone" value="{{ old('phone', $pharmacy->phone) }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email', $pharmacy->email) }}" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Opening hours</label><input name="opening_hours" value="{{ old('opening_hours', $pharmacy->opening_hours) }}" class="form-control" placeholder="Mon-Sun, 8:00 AM - 10:00 PM"></div>
    <div class="col-12"><label class="form-label">About the pharmacy</label><textarea name="description" rows="3" class="form-control">{{ old('description', $pharmacy->description) }}</textarea></div>
  </div>
  <button class="btn btn-primary mt-3">Save changes</button>
</form></div>
@endsection
