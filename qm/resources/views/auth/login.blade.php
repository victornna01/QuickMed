@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="row justify-content-center"><div class="col-md-5 col-lg-4">
  <div class="card p-4">
    <h3 class="mb-3">Login</h3>
    <form method="POST" action="{{ route('login.submit') }}">@csrf
      <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus></div>
      <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
      <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember" id="rm"><label class="form-check-label" for="rm">Remember me</label></div>
      <button class="btn btn-primary w-100">Login</button>
    </form>
    <p class="small text-muted mt-3 mb-0">No account? <a href="{{ route('register') }}">Register</a></p>
  </div>
</div></div>
@endsection
