@extends('layouts.app')

@section('title', 'Kho Sách Thư Viện - Library System')

@section('content')
<!-- Hero Banner tìm kiếm & lọc sách -->
<div class="p-4 p-md-5 mb-4 rounded-4 text-white position-relative overflow-hidden shadow-sm"
     style="background: linear-gradient(135deg, #4d0d17 0%, #821827 70%, #9c2435 100%);">
    <div class="position-relative" style="z-index: 2; max-width: 720px;">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1.5 rounded-pill fw-semibold">
            <i class="bi bi-stars me-1"></i> Không Gian Tri Thức
        </span>
        <h1 class="font-serif display-5 fw-bold mb-2">Khám Phá Kho Tàng Tri Thức</h1>
        <p class="lead mb-4 text-white-50">
            Hàng ngàn đầu sách thuộc nhiều lĩnh vực khoa học, công nghệ, văn học và nghiên cứu đang sẵn sàng phục vụ bạn.
        </p>

        <!-- Search Form AJAX -->
        <form id="catalog-search-form"
      method="GET"
      action="{{ route('books.index') }}"
      data-search="{{ request('search', '') }}"
      data-category="{{ request('category', '') }}"
      data-available="{{ request('available_only') ? '1' : '0' }}"
      class="d-flex flex-column flex-sm-row gap-2">
            <div class="input-group input-group-lg bg-white rounded-pill overflow-hidden p-1 shadow">
                <span class="input-group-text bg-white border-0 ps-3">
                    <i class="bi bi-search text-muted fs-5"></i>
                </span>
                <input type="text"
                       id="catalog-search-input"
                       name="search"
                       class="form-control border-0 shadow-none ps-2"
                       placeholder="Tìm theo tên sách, tác giả..."
                       value="{{ request('search') }}"
                       autocomplete="off">
                <span id="catalog-loading" class="input-group-text bg-white border-0 d-none">
                    <span class="spinner-border spinner-border-sm text-danger" role="status"></span>
                </span>
                <button class="btn btn-lib-primary rounded-pill px-4" type="submit">
                    Tìm Kiếm
                </button>
            </div>
        </form>
    </div>

    <!-- Decorative monogram background -->
    <img src="{{ asset('images/library-logo.png') }}"
         alt="Decor"
         class="position-absolute end-0 top-50 translate-middle-y me-4 d-none d-lg-block opacity-10"
         style="width: 280px; pointer-events: none;">
</div>

<!-- Category Filter Pills -->
<div class="d-flex align-items-center gap-2 overflow-x-auto pb-3 mb-4">
    <span class="text-muted fw-semibold small text-nowrap"><i class="bi bi-funnel me-1"></i> Thể loại:</span>
    <a href="{{ route('books.index', array_filter(['search' => request('search'), 'available_only' => request('available_only')])) }}"
       data-category=""
       class="btn btn-sm {{ !request('category') ? 'btn-lib-primary' : 'btn-outline-secondary' }} rounded-pill text-nowrap px-3 js-category-filter">
        Tất cả
    </a>
    @foreach($categories as $category)
        <a href="{{ route('books.index', array_filter(['category' => $category->id, 'search' => request('search'), 'available_only' => request('available_only')])) }}"
           data-category="{{ $category->id }}"
           class="btn btn-sm {{ request('category') == $category->id ? 'btn-lib-primary' : 'btn-outline-secondary' }} rounded-pill text-nowrap px-3 js-category-filter">
            {{ $category->name }} ({{ $category->books_count }})
        </a>
    @endforeach

    <div class="ms-auto ps-3 text-nowrap">
        <a href="{{ route('books.index', array_filter(['category' => request('category'), 'search' => request('search'), 'available_only' => request('available_only') ? null : 1])) }}"
           id="catalog-available-toggle"
           class="btn btn-sm {{ request('available_only') ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill px-3">
            <i class="bi bi-check2-circle me-1"></i> Chỉ sách còn sẵn
        </a>
    </div>
</div>

<!-- Books Grid Container (Cập nhật bằng AJAX) -->
<div id="books-grid-container" style="transition: opacity 0.2s;">
    @include('books.partials.book-list')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const baseUrl = "{{ route('books.index') }}";
    const searchForm = document.getElementById('catalog-search-form');
    const searchInput = document.getElementById('catalog-search-input');
    const loadingSpinner = document.getElementById('catalog-loading');
    const categoryLinks = document.querySelectorAll('.js-category-filter');
    const availableToggle = document.getElementById('catalog-available-toggle');
    const gridContainer = document.getElementById('books-grid-container');

    // Lưu trạng thái lọc hiện tại để kết hợp chính xác nhiều điều kiện
    const state = {
        search: searchForm.dataset.search || '',
        category: searchForm.dataset.category || '',
        available_only: searchForm.dataset.available === '1'
    };
    
    let debounceTimer = null;
    let currentController = null;

    function updateFilterUI() {
        // Cập nhật màu nút thể loại đang chọn
        categoryLinks.forEach(link => {
            const catId = link.getAttribute('data-category') || '';
            if (catId === String(state.category)) {
                link.classList.remove('btn-outline-secondary');
                link.classList.add('btn-lib-primary');
            } else {
                link.classList.remove('btn-lib-primary');
                link.classList.add('btn-outline-secondary');
            }
        });

        // Cập nhật màu nút Chỉ sách còn sẵn
        if (state.available_only) {
            availableToggle.classList.remove('btn-outline-secondary');
            availableToggle.classList.add('btn-success');
        } else {
            availableToggle.classList.remove('btn-success');
            availableToggle.classList.add('btn-outline-secondary');
        }
    }

    function buildUrl() {
        const url = new URL(baseUrl);
        if (state.search.trim()) url.searchParams.set('search', state.search.trim());
        if (state.category) url.searchParams.set('category', state.category);
        if (state.available_only) url.searchParams.set('available_only', '1');
        return url.toString();
    }

    function fetchBooks(customUrl = null) {
        if (currentController) {
            currentController.abort();
        }
        currentController = new AbortController();

        const targetUrl = customUrl || buildUrl();

        loadingSpinner.classList.remove('d-none');
        gridContainer.style.opacity = '0.5';

        fetch(targetUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            },
            signal: currentController.signal
        })
        .then(response => {
            if (!response.ok) throw new Error('Lỗi tải danh sách sách');
            return response.text();
        })
        .then(html => {
            gridContainer.innerHTML = html;
            window.history.replaceState({}, '', targetUrl);
        })
        .catch(error => {
            if (error.name !== 'AbortError') {
                console.error('Catalog AJAX error:', error);
            }
        })
        .finally(() => {
            loadingSpinner.classList.add('d-none');
            gridContainer.style.opacity = '1';
        });
    }

    // 1. Gõ tìm kiếm realtime (debounce 350ms)
    searchInput.addEventListener('input', function () {
        state.search = this.value;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchBooks(), 350);
    });

    // 2. Bấm Enter hoặc nút Tìm Kiếm
    searchForm.addEventListener('submit', function (e) {
        e.preventDefault();
        clearTimeout(debounceTimer);
        state.search = searchInput.value;
        fetchBooks();
    });

    // 3. Bấm chọn các thẻ Thể loại
    categoryLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            state.category = this.getAttribute('data-category') || '';
            updateFilterUI();
            fetchBooks();
        });
    });

    // 4. Bật / tắt nút Chỉ sách còn sẵn
    availableToggle.addEventListener('click', function (e) {
        e.preventDefault();
        state.available_only = !state.available_only;
        updateFilterUI();
        fetchBooks();
    });

    // 5. Phân trang & Nút "Xem Tất Cả Sách" bằng AJAX
    gridContainer.addEventListener('click', function (e) {
        const pageLink = e.target.closest('.pagination a.page-link');
        if (pageLink) {
            e.preventDefault();
            fetchBooks(pageLink.href);
            return;
        }

        const resetLink = e.target.closest('.js-reset-books-filter');
        if (resetLink) {
            e.preventDefault();
            state.search = '';
            state.category = '';
            state.available_only = false;
            searchInput.value = '';
            updateFilterUI();
            fetchBooks(baseUrl);
        }
    });
});
</script>
@endsection