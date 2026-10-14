@extends('layouts.app')
@section('title', 'Users')
@section('content')
@include('admin._nav')
<form class="row g-2 mb-3"><div class="col-md-5"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name or email"></div>
  <div class="col-md-3"><select name="role" class="form-select"><option value="">All roles</option>@foreach(['customer'=>'Customer','pharmacy_owner'=>'Pharmacy owner','admin'=>'Administrator'] as $k=>$l)<option value="{{ $k }}" @selected(request('role')===$k)>{{ $l }}</option>@endforeach</select></div>
  <div class="col-auto"><button class="btn btn-primary">Filter</button></div></form>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
  <thead class="table-light"><tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Joined</th><th></th></tr></thead><tbody>
  @foreach($users as $u)
    <tr><td>{{ $u->name }}@if($u->pharmacy)<div class="small text-muted">{{ $u->pharmacy->name }}</div>@endif</td><td>{{ $u->email }}</td><td>{{ $u->phone }}</td>
      <td><span class="badge text-bg-light">{{ str_replace('_', ' ', $u->role) }}</span></td><td class="small">{{ $u->created_at->format('d M Y') }}</td>
      <td class="text-end">@unless($u->isAdmin())<form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Delete this user and their data?')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm">Delete</button></form>@endunless</td></tr>
  @endforeach
</tbody></table></div></div>
<div class="mt-3">{{ $users->links('pagination::bootstrap-5') }}</div>
@endsection
