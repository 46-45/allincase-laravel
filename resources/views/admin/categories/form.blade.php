@extends('admin.layout')
@section('title', $category ? 'Edit Category' : 'Create Category')
@section('content')
<h2>{{ $category ? 'Edit' : 'Create' }} Category</h2>
<form method="POST" action="{{ $category ? '/admin/categories/'.$category->id.'/edit' : '/admin/categories/create' }}">
    @csrf
    <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" value="{{ $category?->name }}" required></div>
    <div class="mb-3"><label>Description</label><textarea name="description" class="form-control">{{ $category?->description }}</textarea></div>
    <button class="btn btn-primary">Save</button>
    <a href="/admin/categories" class="btn btn-secondary">Cancel</a>
</form>
@endsection
