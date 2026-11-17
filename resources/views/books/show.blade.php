@extends('layouts.app')

@section('title', $book->title . ' - Chi Tiết Sách')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Kho sách</a></li>
        <li class="breadcrumb-item"><a href="{{ route('books.index', ['category' => $book->category_id]) }}">{{ $book->category->name ?? 'Thể loại' }}</a></li>
        <li class="breadcrumb-item active text-truncate" style="max-width: 300px;" aria-current="page">{{ $book->title }}</li>
    </ol>
</nav>

<div class="row g-4 mb-5">
    <!-- Cột trái: Ảnh bìa & Trạng thái tồn kho -->
    <div class="col-lg-4 text-center">
        <div class="bg-white p-4 rounded-4 border shadow-sm sticky-top" style="top: 2rem;">
            <div class="mb-4 d-flex justify-content-center">
                @if($book->image)
                    <img src="{{ $book->image_url }}"
                         alt="{{ $book->title }}"
                         class="img-fluid rounded-3 shadow"
                         style="max-height: 380px;">
                @else
                    <div class="d-flex flex-column align-items-center justify-content-center p-4 text-center text-white rounded-3 shadow"
                         style="width: 220px; height: 320px; background: linear-gradient(135deg, #821827, #4d0d17);">
                        <i class="bi bi-book-half display-3 mb-3 opacity-75"></i>
                        <h5 class="font-serif fw-bold">{{ $book->title }}</h5>
                        <p class="small opacity-75 mb-0">{{ $book->author->name ?? 'Tác giả' }}</p>
                    </div>
                @endif
            </div>

            <!-- Tình trạng sách -->
            <div class="p-3 rounded-3 mb-3 {{ $book->quantity > 0 ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }}">
                <div class="fw-bold fs-5">
                    @if($book->quantity > 0)
                        <i class="bi bi-check-circle-fill me-1"></i> Còn {{ $book->quantity }} cuốn trong kho
                    @else
                        <i class="bi bi-x-circle-fill me-1"></i> Hiện đã hết sách
                    @endif
                </div>
                <small class="text-muted">Thời hạn mượn tiêu chuẩn: 14 ngày</small>
            </div>

            <!-- Nút hành động mượn sách -->
            @auth
                @if($userHasPendingOrBorrowing)
                    <div class="alert alert-info py-2 mb-0 small">
                        <i class="bi bi-info-circle-fill me-1"></i> Bạn đã có yêu cầu hoặc đang mượn cuốn sách này.
                        <a href="{{ route('borrows.index') }}" class="d-block mt-1 fw-bold">Xem phiếu mượn</a>
                    </div>
                @elseif($book->quantity > 0)
                    <form method="POST" action="{{ route('books.borrow', $book) }}">
                        @csrf
                        <button type="submit" class="btn btn-lib-primary btn-lg w-100 rounded-pill shadow-sm"
                                onclick="return confirm('Xác nhận gửi yêu cầu mượn cuốn sách này?')">
                            <i class="bi bi-bookmark-plus me-1"></i> Mượn Sách Ngay
                        </button>
                    </form>
                @else
                    <button class="btn btn-secondary btn-lg w-100 rounded-pill" disabled>
                        Tạm hết sách
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-lib-primary btn-lg w-100 rounded-pill shadow-sm">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập để mượn sách
                </a>
            @endauth
        </div>
    </div>

    <!-- Cột phải: Thông tin chi tiết -->
    <div class="col-lg-8">
        <div class="bg-white p-4 p-md-5 rounded-4 border shadow-sm mb-4">
            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-1.5 rounded-pill fw-semibold mb-2">
                {{ $book->category->name ?? 'Chưa phân loại' }}
            </span>

            <h1 class="font-serif display-6 fw-bold mb-3">{{ $book->title }}</h1>

            <div class="d-flex flex-wrap gap-4 text-muted border-top border-bottom py-3 mb-4">
                <div>
                    <i class="bi bi-person-fill text-danger me-1"></i>
                    <strong>Tác giả:</strong> {{ $book->author->name ?? 'Chưa rõ' }}
                </div>
                <div>
                    <i class="bi bi-tag-fill text-danger me-1"></i>
                    <strong>Thể loại:</strong> {{ $book->category->name ?? 'Chung' }}
                </div>
                <div>
                    <i class="bi bi-calendar3 text-danger me-1"></i>
                    <strong>Ngày cập nhật:</strong> {{ $book->updated_at ? $book->updated_at->format('d/m/Y') : 'Mới' }}
                </div>
            </div>

            <!-- Tóm tắt nội dung -->
            <h4 class="font-serif fw-bold mb-3">Tóm Tắt Nội Dung</h4>
            <div class="text-secondary leading-relaxed mb-5" style="white-space: pre-line;">
                {{ $book->description ?: 'Cuốn sách này hiện chưa có phần mô tả tóm tắt chi tiết. Vui lòng đến thư viện để đọc thử hoặc liên hệ thủ thư để biết thêm thông tin.' }}
            </div>

            <!-- Giới thiệu tác giả nếu có -->
            @if($book->author && $book->author->bio)
                <div class="p-4 rounded-3 border-start border-4 border-danger bg-light">
                    <h5 class="font-serif fw-bold mb-2">Về Tác Giả: {{ $book->author->name }}</h5>
                    <p class="small text-muted mb-0">{{ $book->author->bio }}</p>
                </div>
            @endif
        </div>

        <!-- Sách liên quan cùng chuyên mục -->
        @if($relatedBooks->count() > 0)
            <div class="mt-5">
                <h4 class="font-serif fw-bold mb-4">Sách Cùng Thể Loại</h4>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
                    @foreach($relatedBooks as $rel)
                        <div class="col">
                            <div class="book-card">
                                <div class="p-3 text-center">
                                    <h6 class="font-serif fw-bold text-truncate-2 mb-2">
                                        <a href="{{ route('books.show', $rel) }}" class="text-dark text-decoration-none">
                                            {{ $rel->title }}
                                        </a>
                                    </h6>
                                    <small class="text-muted d-block text-truncate">{{ $rel->author->name ?? 'Tác giả' }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection