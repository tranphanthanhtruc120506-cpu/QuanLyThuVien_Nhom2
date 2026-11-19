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
        <a href="{{ route('books.index') }}" class="btn btn-lib-primary rounded-pill px-4 js-reset-books-filter">
            Xem Tất Cả Sách
        </a>
    </div>
@endif