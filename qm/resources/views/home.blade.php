@extends('layouts.app')
@section('title', 'Find medicines near you')
@section('content')
<section class="hero rounded-4 p-4 p-md-5 mb-5">
  <div class="row align-items-center">
    <div class="col-lg-8">
      <span class="badge bg-light text-dark mb-3">Search before you move</span>
      <h1 class="display-5 fw-bold">Find the medicine you need, before you leave home.</h1>
      <p class="lead mb-4">Search listed medicines, compare prices and availability across registered pharmacies, then visit the pharmacy or request delivery.</p>
      <form action="{{ route('search') }}" class="row g-2">
        <div class="col-md-6"><input name="q" class="form-control form-control-lg" placeholder="Search a medicine, e.g. Paracetamol" required></div>
        <div class="col-md-4"><input name="area" class="form-control form-control-lg" placeholder="Area (optional)"></div>
        <div class="col-md-2 d-grid"><button class="btn btn-light btn-lg text-primary fw-semibold"><i class="bi bi-search"></i></button></div>
      </form>
    </div>
  </div>
</section>

<div class="row g-3 text-center mb-5">
  @foreach([['bi-search','1. Search','Type the medicine you need.'],['bi-shop','2. Compare','See pharmacies, prices and availability.'],['bi-bag-check','3. Order','Visit the pharmacy or request delivery.']] as $s)
    <div class="col-md-4"><div class="card h-100 p-4"><div class="stat-icon mx-auto mb-3"><i class="bi {{ $s[0] }}"></i></div><h5>{{ $s[1] }}</h5><p class="text-muted mb-0">{{ $s[2] }}</p></div></div>
  @endforeach
</div>

<h4 class="mb-3">Medicines available on QuickMed</h4>
<div class="d-flex flex-wrap gap-2 mb-5">
  @forelse($drugs as $d)<a class="btn btn-outline-primary btn-sm" href="{{ route('search', ['q' => $d->name]) }}">{{ $d->name }}</a>@empty<p class="text-muted">No medicines listed yet.</p>@endforelse
</div>

<div class="d-flex justify-content-between align-items-center mb-3"><h4 class="mb-0">Verified pharmacies</h4><a href="{{ route('pharmacies.index') }}">View all</a></div>
<div class="row g-3 mb-5">
  @forelse($pharmacies as $p)
    <div class="col-md-6 col-lg-4"><div class="card h-100 p-3">
      <h5 class="mb-1">{{ $p->name }} <i class="bi bi-patch-check-fill text-primary" title="Verified"></i></h5>
      <div class="text-muted small mb-2"><i class="bi bi-geo-alt"></i> {{ $p->address }}</div>
      <div class="small mb-3"><i class="bi bi-clock"></i> {{ $p->opening_hours ?? 'Hours not provided' }} &middot; {{ $p->listings_count }} medicines</div>
      <a href="{{ route('pharmacies.show', $p) }}" class="btn btn-outline-primary btn-sm mt-auto">View pharmacy</a>
    </div></div>
  @empty<p class="text-muted">No verified pharmacies yet.</p>@endforelse
</div>

<div class="card p-4 bg-white text-center">
  <h5>Own a pharmacy?</h5>
  <p class="text-muted">Register to list your medicines, update prices and availability, and receive orders and delivery requests.</p>
  <div><a href="{{ route('register') }}" class="btn btn-primary">Register your pharmacy</a></div>
</div>
@endsection
