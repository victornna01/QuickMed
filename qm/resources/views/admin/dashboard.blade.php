@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
@include('admin._nav')
<h2 class="mb-3">Administrator dashboard</h2>
<div class="row g-3 mb-4">
  @foreach([['bi-people','Registered users',$users],['bi-person','Customers',$customers],['bi-patch-check','Verified pharmacies',$verified],['bi-hourglass-split','Awaiting verification',$pending],['bi-capsule','Medicines',$drugs],['bi-receipt','Orders',$orders]] as $c)
    <div class="col-6 col-lg-2"><div class="card p-3 h-100"><div class="stat-icon mb-2"><i class="bi {{ $c[0] }}"></i></div><div class="text-muted small">{{ $c[1] }}</div><div class="fs-4 fw-semibold">{{ $c[2] }}</div></div></div>
  @endforeach
</div>
<div class="row g-3">
  <div class="col-lg-6"><div class="card h-100"><div class="card-header bg-white fw-semibold">Pharmacies awaiting verification</div><ul class="list-group list-group-flush">
    @forelse($pendingPharmacies as $p)
      <li class="list-group-item d-flex justify-content-between align-items-center gap-2">
        <div><b>{{ $p->name }}</b><div class="small text-muted">{{ $p->address }} &middot; {{ $p->owner->name }}</div></div>
        <div class="d-flex gap-1">
          <form method="POST" action="{{ route('admin.pharmacies.update', $p) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="verified"><button class="btn btn-primary btn-sm">Verify</button></form>
          <form method="POST" action="{{ route('admin.pharmacies.update', $p) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="rejected"><button class="btn btn-outline-danger btn-sm">Reject</button></form>
        </div></li>
    @empty<li class="list-group-item text-muted">Nothing waiting for verification.</li>@endforelse
  </ul></div></div>
  <div class="col-lg-6"><div class="card h-100"><div class="card-header bg-white fw-semibold">Orders by status</div><ul class="list-group list-group-flush">
    @foreach(\App\Models\Order::STATUSES as $k => $label)<li class="list-group-item d-flex justify-content-between"><span>{{ $label }}</span><b>{{ $statusCounts[$k] ?? 0 }}</b></li>@endforeach
  </ul></div></div>
  <div class="col-12"><div class="card"><div class="card-header bg-white fw-semibold">Recent orders</div><div class="table-responsive"><table class="table mb-0 align-middle">
    <thead class="table-light"><tr><th>#</th><th>Customer</th><th>Pharmacy</th><th>Medicine</th><th>Status</th></tr></thead><tbody>
    @forelse($recentOrders as $o)<tr><td>{{ $o->id }}</td><td>{{ $o->user->name }}</td><td>{{ $o->pharmacy->name }}</td><td>{{ $o->drug->name }}</td><td><span class="badge text-bg-{{ $o->status_class }}">{{ $o->status_label }}</span></td></tr>
    @empty<tr><td colspan="5" class="text-center text-muted py-3">No orders yet.</td></tr>@endforelse
  </tbody></table></div></div></div>
</div>
@endsection
