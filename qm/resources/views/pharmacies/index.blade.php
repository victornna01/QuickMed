@extends('layouts.app')
@section('title', 'Pharmacies')
@section('content')
<h2 class="mb-3">Registered pharmacies</h2>
<form class="row g-2 mb-4"><div class="col-md-6"><input name="q" value="{{ $s }}" class="form-control" placeholder="Search by name or area"></div><div class="col-auto"><button class="btn btn-primary">Search</button></div></form>
<div class="row g-3">
  @forelse($pharmacies as $p)
    <div class="col-md-6 col-lg-4"><div class="card h-100 p-3">
      <h5>{{ $p->name }} <i class="bi bi-patch-check-fill text-primary"></i></h5>
      <div class="small text-muted mb-1"><i class="bi bi-geo-alt"></i> {{ $p->address }}</div>
      <div class="small text-muted mb-1"><i class="bi bi-telephone"></i> {{ $p->phone }}</div>
      <div class="small mb-3"><i class="bi bi-clock"></i> {{ $p->opening_hours ?? 'Hours not provided' }}</div>
      <div class="mt-auto d-flex justify-content-between align-items-center"><span class="badge text-bg-light">{{ $p->listings_count }} medicines</span><a href="{{ route('pharmacies.show', $p) }}" class="btn btn-outline-primary btn-sm">View</a></div>
    </div></div>
  @empty<p class="text-muted">No pharmacies found.</p>@endforelse
</div>
<div class="mt-4">{{ $pharmacies->links('pagination::bootstrap-5') }}</div>
@endsection
