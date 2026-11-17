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

        <!-- Search Form -->
        <form method="GET" action="{{ route('books.index') }}" class="d-flex flex-column flex-sm-row gap-2">
            <div class="input-group input-group-lg bg-white rounded-pill overflow-hidden p-1 shadow">
                <span class="input-group-text bg-white border-0 ps-3">
                    <i class="bi bi-search text-muted fs-5"></i>
                </span>
                <input type="text"
                       name="search"
                       class="form-control border-0 shadow-none ps-2"
                       placeholder="Tìm theo tên sách, tác giả..."
                       value="{{ request('search') }}">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
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
       class="btn btn-sm {{ !request('category') ? 'btn-lib-primary' : 'btn-outline-secondary' }} rounded-pill text-nowrap px-3">
        Tất cả
    </a>
    @foreach($categories as $category)
        <a href="{{ route('books.index', array_filter(['category' => $category->id, 'search' => request('search'), 'available_only' => request('available_only')])) }}"
           class="btn btn-sm {{ request('category') == $category->id ? 'btn-lib-primary' : 'btn-outline-secondary' }} rounded-pill text-nowrap px-3">
            {{ $category->name }} ({{ $category->books_count }})
        </a>
    @endforeach

    <div class="ms-auto ps-3 text-nowrap">
        <a href="{{ route('books.index', array_filter(['category' => request('category'), 'search' => request('search'), 'available_only' => request('available_only') ? null : 1])) }}"
           class="btn btn-sm {{ request('available_only') ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill px-3">
            <i class="bi bi-check2-circle me-1"></i> Chỉ sách còn sẵn
        </a>
    </div>
</div>

<!-- Books Grid -->
@if($books->count() > 0)
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach($books as $book)
            <div class="col">
                <div class="book-card">
                    <div class="book-cover-wrap">
                        @if($book->image)
                            <img src="{{ $book->image_url }}" alt="{{ $book->title }}" class="book-cover-img">
                        @else
                            <div class="book-cover-img d-flex flex-column align-items-center justify-content-center p-3 text-center text-white"
                                 style="width: 145px; height: 195px; background: linear-gradient(135deg, #821827, #4d0d17);">
                                <i class="bi bi-book-half fs-1 mb-2 opacity-75"></i>
                                <div class="font-serif fw-bold small text-truncate-2">{{ $book->title }}</div>
                                <div class="small opacity-75 text-truncate" style="font-size: 0.72rem;">{{ $book->author->name ?? 'Tác giả' }}</div>
                            </div>
                        @endif

                        @if($book->quantity > 0)
                            <span class="book-stock-badge stock-in">
                                <i class="bi bi-check-circle-fill me-1"></i> Còn {{ $book->quantity }} cuốn
                            </span>
                        @else
                            <span class="book-stock-badge stock-out">
                                <i class="bi bi-x-circle-fill me-1"></i> Đã hết sách
                            </span>
                        @endif
                    </div>

                    <div class="book-card-body">
                        <span class="book-category-tag">
                            {{ $book->category->name ?? 'Chung' }}
                        </span>
                        <h5 class="book-title text-truncate-2" title="{{ $book->title }}">
                            <a href="{{ route('books.show', $book) }}" class="text-dark text-decoration-none">
                                {{ $book->title }}
                            </a>
                        </h5>
                        <p class="book-author">
                            <i class="bi bi-pen me-1"></i> {{ $book->author->name ?? 'Chưa rõ tác giả' }}
                        </p>

                        <div class="book-footer">
                            <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-lib-outline">
                                Chi tiết
                            </a>

                            @auth
                                @if($book->quantity > 0)
                                    <form method="POST" action="{{ route('books.borrow', $book) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-lib-primary"
                                                onclick="return confirm('Bạn có chắc chắn muốn gửi yêu cầu mượn cuốn sách này?')">
                                            <i class="bi bi-bookmark-plus me-1"></i> Mượn sách
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-secondary" disabled>Hết sách</button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary" title="Đăng nhập để mượn sách">
                                    <i class="bi bi-box-arrow-in-right"></i> Mượn
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $books->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="text-center py-5 bg-white rounded-4 border p-5 my-4">
        <i class="bi bi-journal-x fs-1 text-muted d-block mb-3"></i>
        <h4 class="font-serif fw-bold">Không tìm thấy đầu sách phù hợp</h4>
        <p class="text-muted mb-4">Vui lòng thử tìm kiếm với từ khóa khác hoặc bỏ các bộ lọc thể loại đang chọn.</p>
        <a href="{{ route('books.index') }}" class="btn btn-lib-primary rounded-pill px-4">
            Xem Tất Cả Sách
        </a>
    </div>
@endif
@endsection