@extends('layouts.app')

@section('title', 'Quản lý thể loại')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Quản lý thể loại</h4>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Thêm thể loại</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="mb-3" style="max-width: 420px;">
        <x-search-bar placeholder="Tìm theo tên thể loại..." />
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">#</th>
                        <th>Tên thể loại</th>
                        <th>Mô tả</th>
                        <th style="width: 120px;">Số đầu sách</th>
                        <th style="width: 170px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $index => $category)
                        <tr>
                            <td>{{ $categories->firstItem() + $index }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->description ?? '-' }}</td>
                            <td>{{ $category->books_count ?? 0 }}</td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">Sửa</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Bạn chắc chắn muốn xóa thể loại này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Chưa có thể loại nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $categories->links() }}
    </div>
</div>
@endsection