@extends('layouts.app')

@section('title', 'Quản lý thể loại')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="font-serif h3 fw-bold mb-1">Quản lý thể loại</h1>
            <p class="text-muted mb-0">Danh sách thể loại và số đầu sách đang liên kết.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-lib-primary">
            <i class="bi bi-plus-lg me-1"></i> Thêm thể loại
        </a>
    </div>

    <div class="mb-3" style="max-width: 480px;">
        <x-search-bar placeholder="Tìm theo tên thể loại..." />
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3" style="width: 75px;">#</th>
                        <th scope="col">Tên thể loại</th>
                        <th scope="col">Số đầu sách</th>
                        <th scope="col" class="text-end pe-3">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $index => $category)
                        <tr>
                            <td class="ps-3">{{ $categories->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td>{{ $category->books_count }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil me-1"></i> Sửa
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa thể loại này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash me-1"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Không có thể loại phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
