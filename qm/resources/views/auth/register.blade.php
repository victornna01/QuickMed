@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="row justify-content-center"><div class="col-md-8 col-lg-6">
  <div class="card p-4">
    <h3 class="mb-3">Create an account</h3>
    <form method="POST" action="{{ route('register.submit') }}">@csrf
      <div class="mb-3"><label class="form-label">I am registering as</label>
        <select name="role" id="role" class="form-select"><option value="customer" @selected(old('role')==='customer')>Customer (search and order medicines)</option><option value="pharmacy_owner" @selected(old('role')==='pharmacy_owner')>Pharmacy owner (list medicines)</option></select></div>
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Full name</label><input name="name" value="{{ old('name') }}" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" value="{{ old('phone') }}" class="form-control" required></div>
        <div class="col-12"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Password (min 8)</label><input type="password" name="password" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Confirm password</label><input type="password" name="password_confirmation" class="form-control" required></div>
      </div>
      <div id="pharmacyFields" class="mt-3 p-3 rounded" style="background:var(--qm-soft);display:none">
        <h6>Pharmacy details</h6>
        <div class="mb-2"><label class="form-label">Pharmacy name</label><input name="pharmacy_name" value="{{ old('pharmacy_name') }}" class="form-control"></div>
        <div class="mb-2"><label class="form-label">Address</label><input name="pharmacy_address" value="{{ old('pharmacy_address') }}" class="form-control"></div>
        <div><label class="form-label">Area / district</label><input name="pharmacy_area" value="{{ old('pharmacy_area') }}" class="form-control"></div>
        <div class="form-text">Your pharmacy will show publicly after an administrator verifies it.</div>
      </div>
      <button class="btn btn-primary w-100 mt-3">Register</button>
    </form>
    <p class="small text-muted mt-3 mb-0">Already registered? <a href="{{ route('login') }}">Login</a></p>
  </div>
</div></div>
@push('scripts')
<script>
const role = document.getElementById('role'), box = document.getElementById('pharmacyFields');
const sync = () => { box.style.display = role.value === 'pharmacy_owner' ? 'block' : 'none'; };
role.addEventListener('change', sync); sync();
</script>
@endpush
@endsection
