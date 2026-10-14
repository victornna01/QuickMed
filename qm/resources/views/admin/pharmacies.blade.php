@extends('layouts.app')
@section('title', 'Pharmacies')
@section('content')
@include('admin._nav')
<div class="d-flex gap-2 mb-3">
  <a href="{{ route('admin.pharmacies.index') }}" class="btn btn-sm {{ request('status') ? 'btn-outline-secondary' : 'btn-primary' }}">All</a>
  @foreach(['pending','verified','rejected'] as $s)<a href="{{ route('admin.pharmacies.index', ['status' => $s]) }}" class="btn btn-sm text-capitalize {{ request('status') === $s ? 'btn-primary' : 'btn-outline-secondary' }}">{{ $s }}</a>@endforeach
</div>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
  <thead class="table-light"><tr><th>Pharmacy</th><th>Owner</th><th>Address</th><th>Medicines</th><th>Status</th><th></th></tr></thead><tbody>
  @forelse($pharmacies as $p)
    <tr><td><b>{{ $p->name }}</b><div class="small text-muted">{{ $p->phone }}</div></td><td>{{ $p->owner->name }}<div class="small text-muted">{{ $p->owner->email }}</div></td><td class="small">{{ $p->address }}</td><td>{{ $p->listings_count }}</td>
      <td><span class="badge text-bg-{{ ['verified'=>'success','pending'=>'warning','rejected'=>'danger'][$p->status] }}">{{ ucfirst($p->status) }}</span></td>
      <td class="text-end text-nowrap">
        @foreach(['verified' => ['Verify','btn-primary'], 'rejected' => ['Reject','btn-outline-danger'], 'pending' => ['Set pending','btn-outline-secondary']] as $st => $b)
          @if($p->status !== $st)<form method="POST" action="{{ route('admin.pharmacies.update', $p) }}" class="d-inline">@csrf @method('PATCH')<input type="hidden" name="status" value="{{ $st }}"><button class="btn btn-sm {{ $b[1] }}">{{ $b[0] }}</button></form>@endif
        @endforeach
      </td></tr>
  @empty<tr><td colspan="6" class="text-center text-muted py-4">No pharmacies found.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $pharmacies->links('pagination::bootstrap-5') }}</div>
@endsection
