@extends('layouts.app')
@section('title', 'Medicine')
@section('content')
@include('admin._nav')
<div class="card p-4"><h4 class="mb-3">{{ $drug->exists ? 'Edit medicine' : 'Add medicine' }}</h4>
<form method="POST" action="{{ $drug->exists ? route('admin.drugs.update', $drug) : route('admin.drugs.store') }}">@csrf @if($drug->exists) @method('PUT') @endif
  <div class="row g-3">
    <div class="col-md-6"><label class="form-label">Name</label><input name="name" value="{{ old('name', $drug->name) }}" class="form-control" required></div>
    <div class="col-md-3"><label class="form-label">Generic name</label><input name="generic_name" value="{{ old('generic_name', $drug->generic_name) }}" class="form-control"></div>
    <div class="col-md-3"><label class="form-label">Category</label><input name="category" value="{{ old('category', $drug->category) }}" class="form-control"></div>
    <div class="col-12"><label class="form-label">Description</label><textarea name="description" rows="3" class="form-control">{{ old('description', $drug->description) }}</textarea></div>
  </div>
  <div class="mt-4 d-flex gap-2"><button class="btn btn-primary">Save</button><a href="{{ route('admin.drugs.index') }}" class="btn btn-outline-secondary">Cancel</a></div>
</form></div>
@endsection
