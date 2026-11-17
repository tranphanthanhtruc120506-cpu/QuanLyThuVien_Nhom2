@extends('layouts.app')

@section('title', 'Quản lý sách')

@push('styles')
<style>
    .book-admin-page {
        --books-wine: #821827;
        --books-wine-dark: #4d0d17;
        --books-gold: #c99d66;
        --books-border: #ede4d8;
        --books-muted: #786f70;
    }

    .books-hero {
        position: relative;
        overflow: hidden;
        padding: 2rem 2.2rem;
        border-radius: 18px;
        color: #fff;
        background: linear-gradient(125deg, var(--books-wine-dark), var(--books-wine) 72%, #9c2435);
        box-shadow: 0 12px 28px rgba(77, 13, 23, .16);
    }

    .books-hero::after {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        right: 4%;
        top: -85px;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
        box-shadow: 0 0 0 28px rgba(255,255,255,.035), 0 0 0 58px rgba(255,255,255,.025);
        pointer-events: none;
    }

    .books-hero-content { position: relative; z-index: 1; }
    .books-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .32rem .75rem;
        margin-bottom: .85rem;
        color: #3d2b18;
        background: #e8d7be;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .books-hero h1 {
        margin: 0 0 .45rem;
        color: #fff;
        font-family: var(--font-serif, Georgia, serif);
        font-size: clamp(1.8rem, 3vw, 2.5rem);
    }
    .books-hero p { max-width: 620px; margin: 0; color: rgba(255,255,255,.78); }
    .books-total-pill {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        margin-top: 1rem;
        padding: .42rem .8rem;
        border: 1px solid rgba(255,255,255,.22);
        border-radius: 999px;
        background: rgba(255,255,255,.1);
        font-size: .88rem;
    }
    .books-hero-action { position: relative; z-index: 1; white-space: nowrap; }
    .books-hero-action .btn { padding: .7rem 1.1rem; border-radius: 10px; }

    .books-search-card, .books-table-card {
        overflow: hidden;
        border: 1px solid var(--books-border);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 5px 18px rgba(54, 30, 30, .045);
    }
    .books-search-card { padding: 1rem; }
    .books-search-card .input-group-text {
        color: var(--books-muted);
        border-color: #e8e0d8;
        background: #fbfaf8;
    }
    .books-search-card .form-control { min-height: 46px; border-color: #e8e0d8; }
    .books-search-card .form-control:focus {
        border-color: rgba(130,24,39,.45);
        box-shadow: 0 0 0 .2rem rgba(130,24,39,.1);
    }
    .books-search-card .btn-lib-primary { min-width: 100px; }
    .books-search-clear { color: var(--books-muted); font-size: .9rem; }

    .books-table-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid var(--books-border);
    }
    .books-table-heading h2 { margin: 0; font-family: var(--font-serif, Georgia, serif); font-size: 1.2rem; }
    .books-table-heading small { color: var(--books-muted); }
    .books-table-wrap { overflow-x: auto; }
    .books-table { min-width: 760px; margin: 0; }
    .books-table thead th {
        padding: .9rem 1rem;
        color: #6b5557;
        background: #fbf8f4;
        border-bottom: 1px solid var(--books-border);
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .045em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .books-table tbody td { padding: .85rem 1rem; border-color: #f0e9e2; vertical-align: middle; }
    .books-table tbody tr { transition: background .18s ease; }
    .books-table tbody tr:hover { background: #fdfaf7; }
    .book-cover-thumb, .book-cover-empty {
        display: flex;
        width: 48px;
        height: 62px;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 7px;
        background: linear-gradient(145deg, #f4e9dc, #ead9c8);
        color: var(--books-wine);
    }
    .book-cover-thumb { object-fit: cover; box-shadow: 0 3px 9px rgba(50, 20, 20, .12); }
    .book-cover-empty i { font-size: 1.35rem; }
    .book-title-cell { min-width: 190px; }
    .book-title-cell strong { display: block; color: #35282a; font-weight: 700; }
    .book-title-cell small, .book-author-cell small { color: var(--books-muted); }
    .book-category-pill {
        display: inline-flex;
        padding: .32rem .65rem;
        border: 1px solid #ead9c8;
        border-radius: 999px;
        color: var(--books-wine);
        background: #fbf5ef;
        font-size: .8rem;
        white-space: nowrap;
    }
    .book-quantity {
        display: inline-flex;
        min-width: 42px;
        justify-content: center;
        padding: .35rem .55rem;
        border-radius: 9px;
        color: #315d3a;
        background: #edf6ee;
        font-weight: 700;
    }
    .book-quantity.empty { color: #9b3434; background: #fbefee; }
    .book-actions { display: flex; gap: .45rem; white-space: nowrap; }
    .book-actions .btn { border-radius: 8px; }
    .btn-book-edit {
        color: var(--books-wine);
        border: 1px solid #e6cdd0;
        background: #fff;
    }
    .btn-book-edit:hover { color: #fff; border-color: var(--books-wine); background: var(--books-wine); }
    .btn-book-delete { color: #a63838; border: 1px solid #f0d5d2; background: #fff8f7; }
    .btn-book-delete:hover { color: #fff; border-color: #a63838; background: #a63838; }
    .books-empty { padding: 3.5rem 1rem; text-align: center; color: var(--books-muted); }
    .books-empty i { display: block; margin-bottom: .6rem; color: #c7a7a5; font-size: 2.5rem; }
    .books-empty h3 { color: #49383a; font-family: var(--font-serif, Georgia, serif); font-size: 1.25rem; }
    .books-pagination { padding: 1rem 1.25rem; border-top: 1px solid var(--books-border); }
    .books-pagination .pagination { margin: 0; }
    .books-pagination .page-link { color: var(--books-wine); border-color: #eadfe0; }
    .books-pagination .page-item.active .page-link { color: #fff; border-color: var(--books-wine); background: var(--books-wine); }
    .books-pagination .page-link:focus { box-shadow: 0 0 0 .2rem rgba(130,24,39,.12); }
    .books-result-count { color: var(--books-muted); font-size: .88rem; }

    @media (max-width: 575.98px) {
        .books-hero { padding: 1.4rem; }
        .books-hero-row { align-items: flex-start !important; flex-direction: column; }
        .books-hero-action { width: 100%; }
        .books-hero-action .btn { width: 100%; }
        .books-table-heading { align-items: flex-start; flex-direction: column; }
    }
</style>
@endpush

@section('content')
<div class="book-admin-page">
    <section class="books-hero mb-4">
        <div class="books-hero-row d-flex justify-content-between align-items-center gap-4">
            <div class="books-hero-content">
                <span class="books-eyebrow"><i class="bi bi-book-half"></i> Quản trị thư viện</span>
                <h1>Quản lý sách</h1>
                <p>Theo dõi kho sách, cập nhật thông tin đầu sách và quản lý số lượng trong thư viện.</p>
                <span class="books-total-pill"><i class="bi bi-collection"></i> {{ number_format($books->total()) }} đầu sách</span>
            </div>
            <div class="books-hero-action d-flex flex-wrap gap-2">
                <a href="{{ route('admin.authors.index') }}" class="btn btn-outline-light">
                    <i class="bi bi-people me-1"></i> Quản lý tác giả
                </a>
                <a href="{{ route('admin.books.create') }}" class="btn btn-light fw-semibold">
                    <i class="bi bi-plus-lg me-1"></i> Thêm sách
                </a>
            </div>
        </div>
    </section>

    <section class="books-search-card mb-4">
        <form method="GET" action="{{ route('admin.books.index') }}" class="row g-2 align-items-center">
            <div class="col">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input
                        type="search"
                        name="search"
                        class="form-control"
                        placeholder="Tìm theo tên sách hoặc tác giả..."
                        value="{{ request('search') }}"
                        aria-label="Tìm theo tên sách hoặc tác giả"
                    >
                    <button class="btn btn-lib-primary" type="submit">Tìm sách</button>
                </div>
            </div>
            @if (request('search'))
                <div class="col-auto">
                    <a href="{{ route('admin.books.index') }}" class="books-search-clear">
                        <i class="bi bi-x-circle me-1"></i>Xóa tìm kiếm
                    </a>
                </div>
            @endif
        </form>
    </section>

    <section class="books-table-card">
        <div class="books-table-heading">
            <div>
                <h2><i class="bi bi-journal-richtext me-2" style="color: var(--books-wine);"></i>Danh mục sách</h2>
                <small>Thông tin sách, tác giả, thể loại và số lượng hiện có</small>
            </div>
            <span class="books-result-count">
                @if ($books->total() > 0)
                    Hiển thị {{ $books->firstItem() }}–{{ $books->lastItem() }} trong {{ number_format($books->total()) }} kết quả
                @else
                    Không có kết quả
                @endif
            </span>
        </div>

        @if ($books->count() > 0)
            <div class="books-table-wrap">
                <table class="table books-table align-middle">
                    <thead>
                        <tr>
                            <th>Ảnh bìa</th>
                            <th>Tên sách</th>
                            <th>Tác giả</th>
                            <th>Thể loại</th>
                            <th>Số lượng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td>
                                    @if ($book->image)
                                        @php
                                            $coverUrl = str_starts_with($book->image, 'books/')
                                                ? \Illuminate\Support\Facades\Storage::url($book->image)
                                                : asset($book->image);
                                        @endphp
                                        <img src="{{ $coverUrl }}" alt="Bìa {{ $book->title }}" class="book-cover-thumb">
                                    @else
                                        <span class="book-cover-empty" title="Chưa có ảnh bìa"><i class="bi bi-book"></i></span>
                                    @endif
                                </td>
                                <td class="book-title-cell">
                                    <strong>{{ $book->title }}</strong>
                                    <small>Mã sách #{{ str_pad((string) $book->id, 4, '0', STR_PAD_LEFT) }}</small>
                                </td>
                                <td class="book-author-cell">{{ $book->author->name ?? 'Chưa rõ tác giả' }}</td>
                                <td><span class="book-category-pill">{{ $book->category->name ?? 'Chưa phân loại' }}</span></td>
                                <td>
                                    <span class="book-quantity {{ $book->quantity < 1 ? 'empty' : '' }}">
                                        {{ $book->quantity }}
                                    </span>
                                </td>
                                <td>
                                    <div class="book-actions">
                                        <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-book-edit">
                                            <i class="bi bi-pencil me-1"></i>Sửa
                                        </a>
                                        <form action="{{ route('admin.books.destroy', $book) }}"
                                              method="POST"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa sách này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-book-delete">
                                                <i class="bi bi-trash me-1"></i>Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="books-pagination d-flex flex-wrap justify-content-between align-items-center gap-3">
                <span class="books-result-count">Trang {{ $books->currentPage() }} / {{ $books->lastPage() }}</span>
                {{ $books->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="books-empty">
                <i class="bi bi-journal-x"></i>
                <h3>Chưa tìm thấy sách phù hợp</h3>
                <p class="mb-3">Thử từ khóa khác hoặc thêm một đầu sách mới vào thư viện.</p>
                <a href="{{ route('admin.books.create') }}" class="btn btn-lib-primary">
                    <i class="bi bi-plus-lg me-1"></i>Thêm sách mới
                </a>
            </div>
        @endif
    </section>
</div>
@endsection