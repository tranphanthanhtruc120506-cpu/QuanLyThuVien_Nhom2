@extends('layouts.app')

@section('title', 'Sửa thể loại')

@section('content')
<div class="container py-4" style="max-width: 640px;">
    <h4 class="mb-3">Sửa thể loại</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="card card-body">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên thể loại <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
        </div>


        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>
@endsection