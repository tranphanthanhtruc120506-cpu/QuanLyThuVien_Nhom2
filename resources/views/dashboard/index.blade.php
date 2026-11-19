@extends('layouts.app')

@section('title', 'Dashboard Quản trị')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Dashboard Quản trị</h4>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg">
            <div class="card text-bg-light h-100">
                <div class="card-body">
                    <div class="text-muted small">Tổng đầu sách</div>
                    <div class="fs-3 fw-bold">{{ $totalBooks }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg">
            <div class="card text-bg-light h-100">
                <div class="card-body">
                    <div class="text-muted small">Phiếu đang mượn</div>
                    <div class="fs-3 fw-bold text-primary">{{ $borrowingCount }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg">
            <div class="card text-bg-light h-100">
                <div class="card-body">
                    <div class="text-muted small">Phiếu chờ duyệt</div>
                    <div class="fs-3 fw-bold text-warning">{{ $pendingCount }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg">
            <div class="card text-bg-light h-100">
                <div class="card-body">
                    <div class="text-muted small">Phiếu quá hạn</div>
                    <div class="fs-3 fw-bold text-danger">{{ $overdueCount }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg">
            <div class="card text-bg-light h-100">
                <div class="card-body">
                    <div class="text-muted small">Độc giả</div>
                    <div class="fs-3 fw-bold text-success">{{ $readerCount }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <strong>Danh sách sách sắp hết (số lượng &le; 2)</strong>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">#</th>
                        <th>Tên sách</th>
                        <th>Tác giả</th>
                        <th>Thể loại</th>
                        <th style="width: 130px;">Số lượng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lowStockBooks as $i => $book)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $book->title ?? $book->name }}</td>
                            <td>{{ $book->author->name ?? '-' }}</td>
                            <td>{{ $book->category->name ?? '-' }}</td>
                            <td><span class="badge bg-danger">{{ $book->quantity }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Không có sách nào sắp hết.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection