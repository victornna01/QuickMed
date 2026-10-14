@extends('layouts.app')
@section('title', 'Medicines')
@section('content')
@include('admin._nav')
<div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
  <form class="d-flex gap-2"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search medicines"><button class="btn btn-outline-primary">Search</button></form>
  <a href="{{ route('admin.drugs.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add medicine</a>
</div>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
  <thead class="table-light"><tr><th>Name</th><th>Generic name</th><th>Category</th><th>Pharmacies</th><th></th></tr></thead><tbody>
  @forelse($drugs as $d)
    <tr><td><b>{{ $d->name }}</b></td><td>{{ $d->generic_name }}</td><td>{{ $d->category }}</td><td>{{ $d->listings_count }}</td>
      <td class="text-end text-nowrap"><a href="{{ route('admin.drugs.edit', $d) }}" class="btn btn-outline-primary btn-sm">Edit</a>
        <form method="POST" action="{{ route('admin.drugs.destroy', $d) }}" class="d-inline" onsubmit="return confirm('Delete this medicine and all its pharmacy listings?')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm">Delete</button></form></td></tr>
  @empty<tr><td colspan="5" class="text-center text-muted py-4">No medicines found.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $drugs->links('pagination::bootstrap-5') }}</div>
@endsection
