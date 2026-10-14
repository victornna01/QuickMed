@extends('layouts.app')
@section('title', 'Inventory')
@section('content')
@php $cur = env('APP_CURRENCY', '₦'); @endphp
@include('pharmacy._nav')
<div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
  <form class="d-flex gap-2"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search your medicines"><button class="btn btn-outline-primary">Search</button></form>
  <a href="{{ route('pharmacy.inventory.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add medicine</a>
</div>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
  <thead class="table-light"><tr><th>Medicine</th><th>Category</th><th>Price</th><th>Qty</th><th>Status</th><th></th></tr></thead><tbody>
  @forelse($listings as $l)
    <tr>
      <td><b>{{ $l->drug->name }}</b><div class="small text-muted">{{ $l->drug->generic_name }}</div></td><td>{{ $l->drug->category }}</td>
      <td>{{ $cur }}{{ number_format($l->price, 2) }}</td><td>{{ $l->quantity }}</td>
      <td><span class="badge text-bg-{{ $l->availability_class }}">{{ $l->availability_label }}</span></td>
      <td class="text-end text-nowrap"><a href="{{ route('pharmacy.inventory.edit', $l) }}" class="btn btn-outline-primary btn-sm">Edit</a>
        <form method="POST" action="{{ route('pharmacy.inventory.destroy', $l) }}" class="d-inline" onsubmit="return confirm('Remove this medicine?')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm">Remove</button></form></td>
    </tr>
  @empty<tr><td colspan="6" class="text-center text-muted py-4">No medicines yet. Add your first one.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $listings->links('pagination::bootstrap-5') }}</div>
@endsection
