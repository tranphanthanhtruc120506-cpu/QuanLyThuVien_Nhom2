@extends('layouts.app')

@section('title', 'Lịch Sử Mượn Trả - Library System')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="font-serif display-6 fw-bold mb-1">Phiếu Mượn Của Tôi</h1>
        <p class="text-muted mb-0">Theo dõi trạng thái các yêu cầu mượn sách, thời hạn trả và lịch sử hoàn trả.</p>
    </div>
    <a href="{{ route('books.index') }}" class="btn btn-lib-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-1"></i> Mượn Thêm Sách
    </a>
</div>

<!-- Thống kê nhanh của độc giả -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-card-border-primary">
            <i class="bi bi-collection stat-icon"></i>
            <div class="stat-label">Tổng số phiếu</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-card-border-warning">
            <i class="bi bi-hourglass-split stat-icon"></i>
            <div class="stat-label">Chờ thủ thư duyệt</div>
            <div class="stat-value text-warning">{{ $stats['pending'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-card-border-gold">
            <i class="bi bi-book-half stat-icon"></i>
            <div class="stat-label">Sách đang mượn</div>
            <div class="stat-value" style="color: var(--lib-primary);">{{ $stats['borrowing'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-card-border-success">
            <i class="bi bi-check-circle stat-icon"></i>
            <div class="stat-label">Đã hoàn trả</div>
            <div class="stat-value text-success">{{ $stats['returned'] }}</div>
        </div>
    </div>
</div>

<!-- Lọc theo Tab -->
<ul class="nav nav-pills mb-4 border-bottom pb-2">
    <li class="nav-item">
        <a class="nav-link {{ empty($status) ? 'active bg-danger' : 'text-dark' }} rounded-pill px-3 py-1.5"
           href="{{ route('borrows.index') }}">
            Tất cả ({{ $stats['total'] }})
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $status === 'pending' ? 'active bg-danger' : 'text-dark' }} rounded-pill px-3 py-1.5"
           href="{{ route('borrows.index', ['status' => 'pending']) }}">
            Chờ duyệt ({{ $stats['pending'] }})
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $status === 'borrowing' ? 'active bg-danger' : 'text-dark' }} rounded-pill px-3 py-1.5"
           href="{{ route('borrows.index', ['status' => 'borrowing']) }}">
            Đang mượn ({{ $stats['borrowing'] }})
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $status === 'overdue' ? 'active bg-danger' : 'text-dark' }} rounded-pill px-3 py-1.5"
           href="{{ route('borrows.index', ['status' => 'overdue']) }}">
            Quá hạn ({{ $stats['overdue'] }})
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $status === 'returned' ? 'active bg-danger' : 'text-dark' }} rounded-pill px-3 py-1.5"
           href="{{ route('borrows.index', ['status' => 'returned']) }}">
            Đã trả ({{ $stats['returned'] }})
        </a>
    </li>
</ul>

<!-- Bảng danh sách phiếu mượn -->
<div class="bg-white rounded-4 border shadow-sm overflow-hidden">
    @if($records->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Tên Sách</th>
                        <th>Thể Loại</th>
                        <th>Ngày Mượn</th>
                        <th>Hạn Trả</th>
                        <th>Ngày Trả Thực Tế</th>
                        <th>Trạng Thái</th>
                        <th class="text-end pe-4">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $rec)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 rounded bg-light text-danger">
                                        <i class="bi bi-book fs-4"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('books.show', $rec->book) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $rec->book->title }}
                                        </a>
                                        <div class="small text-muted">{{ $rec->book->author->name ?? 'Tác giả' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $rec->book->category->name ?? 'Chung' }}
                                </span>
                            </td>
                            <td>
                                {{ $rec->borrow_date ? $rec->borrow_date->format('d/m/Y') : '—' }}
                            </td>
                            <td>
                                @if($rec->due_date)
                                    <span class="{{ $rec->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                        {{ $rec->due_date->format('d/m/Y') }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                {{ $rec->return_date ? $rec->return_date->format('d/m/Y') : '—' }}
                            </td>
                            <td>
                                @if($rec->status === \App\Models\BorrowRecord::PENDING)
                                    <span class="badge-status badge-pending">
                                        <i class="bi bi-hourglass-split"></i> Chờ duyệt
                                    </span>
                                @elseif($rec->status === \App\Models\BorrowRecord::BORROWING)
                                    @if($rec->isOverdue())
                                        <span class="badge-status badge-overdue">
                                            <i class="bi bi-exclamation-octagon"></i> Quá hạn trả
                                        </span>
                                    @else
                                        <span class="badge-status badge-borrowing">
                                            <i class="bi bi-book-half"></i> Đang mượn
                                        </span>
                                    @endif
                                @elseif($rec->status === \App\Models\BorrowRecord::RETURNED)
                                    <span class="badge-status badge-returned">
                                        <i class="bi bi-check2-circle"></i> Đã hoàn trả
                                    </span>
                                @elseif($rec->status === \App\Models\BorrowRecord::REJECTED)
                                    <span class="badge-status badge-rejected">
                                        <i class="bi bi-x-circle"></i> Bị từ chối
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if($rec->status === \App\Models\BorrowRecord::PENDING)
                                    <form method="POST" action="{{ route('borrows.cancel', $rec) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Bạn có chắc muốn hủy yêu cầu mượn này?')">
                                            <i class="bi bi-trash"></i> Hủy
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('books.show', $rec->book) }}" class="btn btn-sm btn-light">
                                        <i class="bi bi-eye"></i> Xem sách
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-3 border-top d-flex justify-content-center">
            {{ $records->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
            <h5 class="font-serif fw-bold">Chưa có phiếu mượn nào</h5>
            <p class="text-muted mb-3">Bạn chưa có phiếu mượn sách nào trong danh mục này.</p>
            <a href="{{ route('books.index') }}" class="btn btn-lib-primary rounded-pill px-4">
                Xem Thư Viện Sách
            </a>
        </div>
    @endif
</div>
@endsection
