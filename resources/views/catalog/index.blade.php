@extends('layouts.app')

@section('title', 'Danh mục sách')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h4 class="mb-0">Danh mục sách</h4>
        <div style="min-width: 280px;">
            <x-search-bar placeholder="Tìm theo tên sách hoặc tác giả..." />
        </div>
    </div>

    {{-- Lọc theo thể loại --}}
    <div class="mb-4 d-flex flex-wrap gap-2">
        <a href="{{ route('books.index', request()->only('q')) }}"
           class="btn btn-sm {{ !request('category_id') ? 'btn-primary' : 'btn-outline-primary' }}">
            Tất cả thể loại
        </a>

        @foreach ($categories as $category)
            <a href="{{ route('books.index', array_merge(request()->only('q'), ['category_id' => $category->id])) }}"
               class="btn btn-sm {{ (string) request('category_id') === (string) $category->id ? 'btn-primary' : 'btn-outline-primary' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    {{-- Lưới 12 sách --}}
    <div class="row g-4">
        @forelse ($books as $book)
            @php
                $coverUrl = null;
                if (!empty($book->cover_image)) {
                    $coverUrl = \Illuminate\Support\Str::startsWith($book->cover_image, ['http://', 'https://', '/'])
                        ? $book->cover_image
                        : asset('storage/' . $book->cover_image);
                }
            @endphp

            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100">
                    @if ($coverUrl)
                        <img src="{{ $coverUrl }}" class="card-img-top" alt="{{ $book->title ?? $book->name }}"
                             style="height: 240px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center text-muted"
                             style="height: 240px;">
                            Chưa có ảnh bìa
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-1">{{ $book->title ?? $book->name }}</h6>
                        <p class="text-muted small mb-2">
                            Tác giả: {{ $book->author->name ?? 'Đang cập nhật' }}
                        </p>
                        <p class="small mb-3">
                            @if (($book->quantity ?? 0) > 0)
                                <span class="badge bg-success">Còn sách ({{ $book->quantity }})</span>
                            @else
                                <span class="badge bg-secondary">Hết sách</span>
                            @endif
                        </p>

                        <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary mt-auto">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center mb-0">
                    Không tìm thấy sách phù hợp.
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $books->links() }}
    </div>
</div>
@endsection