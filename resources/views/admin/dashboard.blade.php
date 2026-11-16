@extends('layouts.app')

@section('title', 'Bảng Điều Khiển Quản Trị - Library System')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-1.5 rounded-pill fw-semibold mb-1">
            <i class="bi bi-shield-check me-1"></i> Bảng Quản Trị Hệ Thống
        </span>
        <h1 class="font-serif display-6 fw-bold mb-0">Tổng Quan Thư Viện</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.borrows') }}" class="btn btn-lib-primary rounded-pill px-3">
            <i class="bi bi-card-checklist me-1"></i> Quản Lý Phiếu Mượn
        </a>
        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-book me-1"></i> Xem Kho Sách
        </a>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-card-border-primary">
            <i class="bi bi-book stat-icon"></i>
            <div class="stat-label">Tổng đầu sách</div>
            <div class="stat-value">{{ $stats['total_books'] }}</div>
            <small class="text-muted d-block mt-2"><i class="bi bi-layers me-1"></i> Kho tài liệu thư viện</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-card-border-warning">
            <i class="bi bi-hourglass-split stat-icon"></i>
            <div class="stat-label">Chờ duyệt mượn</div>
            <div class="stat-value text-warning">{{ $stats['total_pending'] }}</div>
            <small class="text-muted d-block mt-2"><i class="bi bi-clock me-1"></i> Cần xử lý sớm</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-card-border-gold">
            <i class="bi bi-journal-arrow-up stat-icon"></i>
            <div class="stat-label">Đang cho mượn</div>
            <div class="stat-value" style="color: var(--lib-primary);">{{ $stats['total_borrowing'] }}</div>
            <small class="text-muted d-block mt-2"><i class="bi bi-person-check me-1"></i> Đang lưu hành</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-card-border-success">
            <i class="bi bi-people stat-icon"></i>
            <div class="stat-label">Độc giả đăng ký</div>
            <div class="stat-value text-success">{{ $stats['total_users'] }}</div>
            <small class="text-muted d-block mt-2"><i class="bi bi-person-badge me-1"></i> Tài khoản độc giả</small>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Cột trái: Yêu cầu chờ duyệt -->
    <div class="col-lg-7">
        <div class="bg-white rounded-4 border shadow-sm p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-serif fw-bold mb-0">
                    <i class="bi bi-hourglass-split text-warning me-2"></i>
                    Yêu Cầu Chờ Duyệt Mới Nhất
                </h5>
                <a href="{{ route('admin.borrows', ['status' => 'pending']) }}" class="small fw-semibold">
                    Xem tất cả ({{ $stats['total_pending'] }})
                </a>
            </div>

            @if($pendingRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Độc Giả</th>
                                <th>Sách Yêu Cầu</th>
                                <th>Thời Gian</th>
                                <th class="text-end">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingRecords as $rec)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $rec->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $rec->user->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div class="text-truncate fw-semibold" style="max-width: 180px;" title="{{ $rec->book->title ?? '' }}">
                                            {{ $rec->book->title ?? 'N/A' }}
                                        </div>
                                        <small class="text-muted">Kho còn: {{ $rec->book->quantity ?? 0 }} cuốn</small>
                                    </td>
                                    <td class="small text-muted">
                                        {{ $rec->created_at ? $rec->created_at->diffForHumans() : 'Mới' }}
                                    </td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('admin.borrows.approve', $rec) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Duyệt cho mượn">
                                                <i class="bi bi-check-lg"></i> Duyệt
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.borrows.reject', $rec) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Từ chối">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-check2-all fs-2 text-success d-block mb-2"></i>
                    Tất cả các yêu cầu mượn sách đã được giải quyết xong!
                </div>
            @endif
        </div>
    </div>

    <!-- Cột phải: Cảnh báo sách quá hạn & Hệ thống -->
    <div class="col-lg-5">
        <div class="bg-white rounded-4 border shadow-sm p-4 mb-4">
            <h5 class="font-serif fw-bold mb-3 text-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Cảnh Báo Quá Hạn Trả ({{ $stats['total_overdue'] }})
            </h5>

            @if($overdueRecords->count() > 0)
                <ul class="list-group list-group-flush">
                    @foreach($overdueRecords as $overdue)
                        <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold text-danger">{{ $overdue->book->title ?? 'N/A' }}</div>
                                <small class="text-muted">
                                    Độc giả: {{ $overdue->user->name }} • Hạn trả: {{ $overdue->due_date ? $overdue->due_date->format('d/m/Y') : '' }}
                                </small>
                            </div>
                            <form method="POST" action="{{ route('admin.borrows.return', $overdue) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Thu hồi / Đã nhận lại">
                                    <i class="bi bi-check-circle"></i> Đã trả
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted small mb-0">Hiện không có cuốn sách nào bị quá hạn trả.</p>
            @endif
        </div>

        <!-- Thông tin máy chủ & nghiệp vụ thư viện -->
        <div class="p-4 rounded-4 text-white" style="background: linear-gradient(135deg, #4d0d17 0%, #821827 100%);">
            <h5 class="font-serif fw-bold mb-2">Quy Trình Quản Lý Mượn Trả</h5>
            <p class="small text-white-50 mb-3">
                1. Độc giả gửi yêu cầu mượn qua hệ thống trực tuyến.<br>
                2. Thủ thư phê duyệt dựa trên lượng sách trong kho.<br>
                3. Độc giả nhận sách trực tiếp và hoàn trả trong 14 ngày.
            </p>
            <div class="d-flex gap-2">
                <span class="badge bg-white bg-opacity-20 text-white"><i class="bi bi-cpu"></i> Laravel SSR</span>
                <span class="badge bg-white bg-opacity-20 text-white"><i class="bi bi-palette"></i> #821827 Wine</span>
            </div>
        </div>
    </div>
</div>
@endsection
