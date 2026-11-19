@extends('layouts.app')

@section('title', 'Quản Lý Phiếu Mượn Sách - Library System')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="font-serif display-6 fw-bold mb-1">Quản Lý Toàn Bộ Phiếu Mượn</h1>
        <p class="text-muted mb-0">Theo dõi, phê duyệt, từ chối và xác nhận thu hồi sách từ độc giả.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Về Dashboard
    </a>
</div>

<!-- Bộ lọc & Tìm kiếm AJAX -->
<div class="bg-white p-3 p-md-4 rounded-4 border shadow-sm mb-4">
    <form id="borrow-filter-form" method="GET" action="{{ route('admin.borrows') }}" class="row g-3 align-items-center">
        <div class="col-md-6 col-lg-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text"
                       id="borrow-search-input"
                       name="search"
                       class="form-control border-start-0"
                       placeholder="Gõ tên độc giả, email hoặc tên sách để tìm ngay..."
                       value="{{ request('search') }}"
                       autocomplete="off">
                <span id="ajax-loading" class="input-group-text bg-white border-start-0 d-none">
                    <span class="spinner-border spinner-border-sm text-danger" role="status"></span>
                </span>
            </div>
        </div>

        <div class="col-md-4 col-lg-4">
            <select id="borrow-status-select" name="status" class="form-select">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ duyệt (Pending)</option>
                <option value="borrowing" {{ request('status') === 'borrowing' ? 'selected' : '' }}>Đang mượn (Borrowing)</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Quá hạn trả (Overdue)</option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Đã hoàn trả (Returned)</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Bị từ chối (Rejected)</option>
            </select>
        </div>

        <div class="col-md-2 col-lg-3 d-flex gap-2">
            <button type="submit" class="btn btn-lib-primary rounded-pill px-3">Lọc</button>
            <button type="button" id="borrow-reset-btn" class="btn btn-light rounded-pill px-3">Đặt lại</button>
        </div>
    </form>
</div>

<!-- Vùng bảng quản lý phiếu mượn được cập nhật bằng AJAX -->
<div id="borrows-table-container" class="bg-white rounded-4 border shadow-sm overflow-hidden" style="transition: opacity 0.2s;">
    @include('admin.partials.borrows-table')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('borrow-filter-form');
    const searchInput = document.getElementById('borrow-search-input');
    const statusSelect = document.getElementById('borrow-status-select');
    const resetBtn = document.getElementById('borrow-reset-btn');
    const tableContainer = document.getElementById('borrows-table-container');
    const loadingSpinner = document.getElementById('ajax-loading');

    let debounceTimer = null;
    let currentController = null;

    function fetchBorrows(customUrl = null) {
        if (currentController) {
            currentController.abort();
        }
        currentController = new AbortController();

        let targetUrl;
        if (customUrl) {
            targetUrl = customUrl;
        } else {
            const url = new URL(form.action);
            const searchVal = searchInput.value.trim();
            const statusVal = statusSelect.value;

            if (searchVal) url.searchParams.set('search', searchVal);
            if (statusVal) url.searchParams.set('status', statusVal);
            targetUrl = url.toString();
        }

        loadingSpinner.classList.remove('d-none');
        tableContainer.style.opacity = '0.5';

        fetch(targetUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            },
            signal: currentController.signal
        })
        .then(response => {
            if (!response.ok) throw new Error('Lỗi tải dữ liệu');
            return response.text();
        })
        .then(html => {
            tableContainer.innerHTML = html;
            window.history.replaceState({}, '', targetUrl);
        })
        .catch(error => {
            if (error.name !== 'AbortError') {
                console.error('AJAX search error:', error);
            }
        })
        .finally(() => {
            loadingSpinner.classList.add('d-none');
            tableContainer.style.opacity = '1';
        });
    }

    // 1. Tự động tìm kiếm khi gõ phím (chờ 350ms sau khi ngừng gõ)
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchBorrows(), 350);
    });

    // 2. Tự động lọc khi đổi trạng thái
    statusSelect.addEventListener('change', function () {
        fetchBorrows();
    });

    // 3. Chặn submit tải lại trang khi bấm Enter hoặc nút Lọc
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearTimeout(debounceTimer);
        fetchBorrows();
    });

    // 4. Nút Đặt lại bộ lọc bằng AJAX
    resetBtn.addEventListener('click', function () {
        searchInput.value = '';
        statusSelect.value = '';
        fetchBorrows(form.action);
    });

    // 5. Phân trang bằng AJAX (không load lại trang khi bấm chuyển trang)
    tableContainer.addEventListener('click', function (e) {
        const pageLink = e.target.closest('.pagination a.page-link');
        if (pageLink) {
            e.preventDefault();
            fetchBorrows(pageLink.href);
        }
    });
});
</script>
@endsection