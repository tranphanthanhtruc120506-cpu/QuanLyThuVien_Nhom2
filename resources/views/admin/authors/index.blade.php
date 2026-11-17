@extends('layouts.app')

@section('title', 'Quản lý tác giả')

@push('styles')
<style>
    .author-admin-page { --author-wine: #821827; --author-wine-dark: #4d0d17; --author-border: #ede4d8; --author-muted: #786f70; }
    .author-hero { position: relative; overflow: hidden; padding: 2rem 2.2rem; border-radius: 18px; color: #fff; background: linear-gradient(125deg, var(--author-wine-dark), var(--author-wine) 72%, #9c2435); box-shadow: 0 12px 28px rgba(77,13,23,.15); }
    .author-hero::after { content: ""; position: absolute; width: 210px; height: 210px; right: 5%; top: -75px; border: 1px solid rgba(255,255,255,.13); border-radius: 50%; box-shadow: 0 0 0 26px rgba(255,255,255,.035), 0 0 0 54px rgba(255,255,255,.025); pointer-events: none; }
    .author-hero-content, .author-hero-actions { position: relative; z-index: 1; }
    .author-eyebrow { display: inline-flex; gap: .4rem; align-items: center; padding: .32rem .75rem; margin-bottom: .8rem; border-radius: 999px; color: #3d2b18; background: #e8d7be; font-size: .77rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
    .author-hero h1 { margin: 0 0 .4rem; color: #fff; font-family: var(--font-serif, Georgia, serif); font-size: clamp(1.8rem, 3vw, 2.4rem); }
    .author-hero p { margin: 0; color: rgba(255,255,255,.78); }
    .author-hero-actions { display: flex; gap: .55rem; flex-wrap: wrap; }
    .author-hero-actions .btn { border-radius: 9px; white-space: nowrap; }
    .author-search-card, .author-table-card { overflow: hidden; border: 1px solid var(--author-border); border-radius: 16px; background: #fff; box-shadow: 0 5px 18px rgba(54,30,30,.045); }
    .author-search-card { padding: 1rem; }
    .author-search-card .input-group-text { color: var(--author-muted); border-color: #e8e0d8; background: #fbfaf8; }
    .author-search-card .form-control { min-height: 46px; border-color: #e8e0d8; }
    .author-search-card .form-control:focus { border-color: rgba(130,24,39,.45); box-shadow: 0 0 0 .2rem rgba(130,24,39,.1); }
    .author-table-heading { display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--author-border); }
    .author-table-heading h2 { margin: 0; font-family: var(--font-serif, Georgia, serif); font-size: 1.2rem; }
    .author-muted { color: var(--author-muted); font-size: .88rem; }
    .author-table-wrap { overflow-x: auto; }
    .author-table { min-width: 680px; margin: 0; }
    .author-table thead th { padding: .9rem 1rem; color: #6b5557; background: #fbf8f4; border-bottom: 1px solid var(--author-border); font-size: .78rem; font-weight: 700; letter-spacing: .045em; text-transform: uppercase; white-space: nowrap; }
    .author-table tbody td { padding: .9rem 1rem; border-color: #f0e9e2; vertical-align: middle; }
    .author-table tbody tr:hover { background: #fdfaf7; }
    .author-name { color: #35282a; font-weight: 700; }
    .author-bio { max-width: 440px; color: #6e6566; line-height: 1.55; }
    .author-count { display: inline-flex; min-width: 38px; justify-content: center; padding: .35rem .55rem; border-radius: 9px; color: var(--author-wine); background: #fbf0ef; font-weight: 700; }
    .author-actions { display: flex; gap: .45rem; white-space: nowrap; }
    .author-actions .btn { border-radius: 8px; }
    .author-edit-btn { color: var(--author-wine); border: 1px solid #e6cdd0; background: #fff; }
    .author-edit-btn:hover { color: #fff; border-color: var(--author-wine); background: var(--author-wine); }
    .author-delete-btn { color: #a63838; border: 1px solid #f0d5d2; background: #fff8f7; }
    .author-delete-btn:hover { color: #fff; border-color: #a63838; background: #a63838; }
    .author-empty { padding: 3.2rem 1rem; text-align: center; color: var(--author-muted); }
    .author-empty i { display: block; margin-bottom: .6rem; color: #c7a7a5; font-size: 2.5rem; }
    .author-empty h3 { color: #49383a; font-family: var(--font-serif, Georgia, serif); font-size: 1.25rem; }
    .author-pagination { padding: 1rem 1.25rem; border-top: 1px solid var(--author-border); }
    .author-pagination .pagination { margin: 0; }
    .author-pagination .page-link { color: var(--author-wine); border-color: #eadfe0; }
    .author-pagination .page-item.active .page-link { color: #fff; border-color: var(--author-wine); background: var(--author-wine); }
    @media (max-width: 575.98px) { .author-hero { padding: 1.4rem; } .author-hero-row { align-items: flex-start !important; flex-direction: column; } .author-hero-actions { width: 100%; } .author-hero-actions .btn { flex: 1; } .author-table-heading { align-items: flex-start; flex-direction: column; } }
</style>
@endpush

@section('content')
<div class="author-admin-page">
    <section class="author-hero mb-4">
        <div class="author-hero-row d-flex justify-content-between align-items-center gap-4">
            <div class="author-hero-content">
                <span class="author-eyebrow"><i class="bi bi-people"></i> Quản trị thư viện</span>
                <h1>Quản lý tác giả</h1>
                <p>Cập nhật hồ sơ tác giả và xem số đầu sách đang liên kết.</p>
            </div>
            <div class="author-hero-actions">
                <a href="{{ route('admin.books.index') }}" class="btn btn-outline-light"><i class="bi bi-book me-1"></i> Quản lý sách</a>
                <a href="{{ route('admin.authors.create') }}" class="btn btn-light fw-semibold"><i class="bi bi-plus-lg me-1"></i> Thêm tác giả</a>
            </div>
        </div>
    </section>

    <section class="author-search-card mb-4">
        <form method="GET" action="{{ route('admin.authors.index') }}" class="row g-2 align-items-center">
            <div class="col">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Tìm theo tên hoặc giới thiệu tác giả..." aria-label="Tìm tác giả">
                    <button class="btn btn-lib-primary" type="submit">Tìm tác giả</button>
                </div>
            </div>
            @if (request('search'))
                <div class="col-auto"><a href="{{ route('admin.authors.index') }}" class="author-muted"><i class="bi bi-x-circle me-1"></i>Xóa tìm kiếm</a></div>
            @endif
        </form>
    </section>

    <section class="author-table-card">
        <div class="author-table-heading">
            <div>
                <h2><i class="bi bi-person-lines-fill me-2" style="color:#821827"></i>Danh mục tác giả</h2>
                <span class="author-muted">Thông tin tác giả và số sách đang được liên kết</span>
            </div>
            <span class="author-muted">{{ number_format($authors->total()) }} tác giả</span>
        </div>

        @if ($authors->count())
            <div class="author-table-wrap">
                <table class="table author-table align-middle">
                    <thead>
                        <tr><th>Tác giả</th><th>Giới thiệu</th><th>Số sách</th><th>Thao tác</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($authors as $author)
                            <tr>
                                <td><span class="author-name">{{ $author->name }}</span></td>
                                <td class="author-bio">{{ $author->bio ? \Illuminate\Support\Str::limit($author->bio, 120) : 'Chưa có giới thiệu' }}</td>
                                <td><span class="author-count">{{ $author->books_count }}</span></td>
                                <td>
                                    <div class="author-actions">
                                        <a href="{{ route('admin.authors.edit', $author) }}" class="btn btn-sm author-edit-btn"><i class="bi bi-pencil me-1"></i>Sửa</a>
                                        <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa tác giả này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm author-delete-btn"><i class="bi bi-trash me-1"></i>Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="author-pagination d-flex flex-wrap justify-content-between align-items-center gap-3">
                <span class="author-muted">Trang {{ $authors->currentPage() }} / {{ $authors->lastPage() }}</span>
                {{ $authors->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="author-empty">
                <i class="bi bi-person-x"></i>
                <h3>Chưa tìm thấy tác giả</h3>
                <p class="mb-3">Thử từ khóa khác hoặc thêm tác giả mới.</p>
                <a href="{{ route('admin.authors.create') }}" class="btn btn-lib-primary"><i class="bi bi-plus-lg me-1"></i>Thêm tác giả</a>
            </div>
        @endif
    </section>
</div>
@endsection