@extends('layouts.app')

@section('title', 'Quản lý độc giả')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Quản lý độc giả</h4>
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
        <x-search-bar placeholder="Tìm theo tên hoặc email..." />
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">#</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th style="width: 140px;">Trạng thái</th>
                        <th style="width: 150px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->is_active)
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-danger">Đang khóa</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->role !== 'admin' && auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                            {{ $user->is_active ? 'Khóa' : 'Mở khóa' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Không khả dụng</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Không tìm thấy độc giả nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection