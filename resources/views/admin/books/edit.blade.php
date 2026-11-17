@extends('layouts.app')

@section('title', 'Sửa sách')

@push('styles')
<style>
    .book-edit-page { --edit-wine: #821827; --edit-wine-dark: #4d0d17; --edit-border: #ede4d8; --edit-muted: #786f70; }
    .book-edit-hero { position: relative; overflow: hidden; padding: 1.75rem 2rem; border-radius: 18px; color: #fff; background: linear-gradient(125deg, var(--edit-wine-dark), var(--edit-wine) 72%, #9c2435); box-shadow: 0 12px 28px rgba(77,13,23,.15); }
    .book-edit-hero::after { content: ""; position: absolute; width: 200px; height: 200px; right: 7%; top: -75px; border: 1px solid rgba(255,255,255,.13); border-radius: 50%; box-shadow: 0 0 0 25px rgba(255,255,255,.035), 0 0 0 52px rgba(255,255,255,.025); pointer-events: none; }
    .book-edit-hero-content, .book-edit-hero-action { position: relative; z-index: 1; }
    .book-edit-eyebrow { display: inline-flex; align-items: center; gap: .4rem; padding: .3rem .7rem; margin-bottom: .65rem; border-radius: 999px; color: #3d2b18; background: #e8d7be; font-size: .76rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
    .book-edit-hero h1 { margin: 0 0 .35rem; color: #fff; font-family: var(--font-serif, Georgia, serif); font-size: clamp(1.7rem, 3vw, 2.2rem); }
    .book-edit-hero p { margin: 0; color: rgba(255,255,255,.78); }
    .book-edit-hero .btn { border-radius: 9px; white-space: nowrap; }
    .book-edit-card { overflow: hidden; border: 1px solid var(--edit-border); border-radius: 16px; background: #fff; box-shadow: 0 6px 20px rgba(54,30,30,.05); }
    .book-edit-card-head { padding: 1.1rem 1.4rem; border-bottom: 1px solid var(--edit-border); background: #fcfaf7; }
    .book-edit-card-head h2 { margin: 0; color: #382b2d; font-family: var(--font-serif, Georgia, serif); font-size: 1.15rem; }
    .book-edit-card-head p { margin: .25rem 0 0; color: var(--edit-muted); font-size: .88rem; }
    .book-edit-card-body { padding: 1.5rem; }
    .book-edit-page .form-label { margin-bottom: .45rem; color: #4a3a3c; font-size: .91rem; font-weight: 700; }
    .book-edit-page .form-control, .book-edit-page .form-select { min-height: 45px; border-color: #e7ded6; border-radius: 9px; background-color: #fff; }
    .book-edit-page textarea.form-control { min-height: auto; }
    .book-edit-page .form-control:focus, .book-edit-page .form-select:focus { border-color: rgba(130,24,39,.5); box-shadow: 0 0 0 .2rem rgba(130,24,39,.1); }
    .book-edit-page .form-text { color: var(--edit-muted); font-size: .82rem; }
    .book-edit-required { color: #a63838; }
    .book-edit-image-panel { display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 1px dashed #d8c5b4; border-radius: 12px; background: #fcfaf7; }
    .book-edit-cover { width: 82px; height: 108px; flex: 0 0 auto; object-fit: cover; border: 1px solid var(--edit-border); border-radius: 8px; background: #f4e9dc; box-shadow: 0 4px 12px rgba(50,20,20,.12); }
    .book-edit-no-cover { display: flex; width: 82px; height: 108px; flex: 0 0 auto; align-items: center; justify-content: center; border: 1px solid var(--edit-border); border-radius: 8px; color: var(--edit-wine); background: linear-gradient(145deg,#f4e9dc,#ead9c8); font-size: 1.7rem; }
    .book-edit-actions { display: flex; justify-content: flex-end; gap: .65rem; padding-top: 1.15rem; margin-top: .5rem; border-top: 1px solid #f0e9e2; }
    .book-edit-actions .btn { min-width: 125px; padding: .62rem 1rem; border-radius: 9px; }
    .book-edit-back { color: var(--edit-wine); border: 1px solid #e6cdd0; background: #fff; }
    .book-edit-back:hover { color: var(--edit-wine-dark); border-color: var(--edit-wine); background: #fbf5f4; }
    @media (max-width: 575.98px) { .book-edit-hero { padding: 1.35rem; } .book-edit-hero-row { align-items: flex-start !important; flex-direction: column; } .book-edit-card-body { padding: 1.1rem; } .book-edit-image-panel { align-items: flex-start; flex-direction: column; } .book-edit-actions { flex-direction: column-reverse; } .book-edit-actions .btn { width: 100%; } }
</style>
@endpush

@section('content')
<div class="book-edit-page">
    <section class="book-edit-hero mb-4">
        <div class="book-edit-hero-row d-flex justify-content-between align-items-center gap-4">
            <div class="book-edit-hero-content">
                <span class="book-edit-eyebrow"><i class="bi bi-pencil-square"></i> Kho sách thư viện</span>
                <h1>Sửa thông tin sách</h1>
                <p>Cập nhật thông tin cho “{{ $book->title }}”.</p>
            </div>
            <a href="{{ route('admin.books.index') }}" class="btn btn-light book-edit-hero-action">
                <i class="bi bi-arrow-left me-1"></i> Danh sách sách
            </a>
        </div>
    </section>

    <section class="book-edit-card">
        <div class="book-edit-card-head">
            <h2><i class="bi bi-journal-text me-2" style="color:#821827"></i>Thông tin đầu sách</h2>
            <p>Các mục có dấu <span class="book-edit-required">*</span> là bắt buộc.</p>
        </div>

        <div class="book-edit-card-body">
            <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-lg-8">
                        <label for="title" class="form-label">Tên sách <span class="book-edit-required">*</span></label>
                        <input id="title" type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $book->title) }}" required maxlength="255">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-lg-4">
                        <label for="quantity" class="form-label">Số lượng <span class="book-edit-required">*</span></label>
                        <input id="quantity" type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" min="0" value="{{ old('quantity', $book->quantity) }}" required>
                        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="author_id" class="form-label">Tác giả <span class="book-edit-required">*</span></label>
                        <select id="author_id" name="author_id" class="form-select @error('author_id') is-invalid @enderror" required>
                            <option value="">Chọn tác giả</option>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" @selected(old('author_id', $book->author_id) == $author->id)>{{ $author->name }}</option>
                            @endforeach
                        </select>
                        @error('author_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Thể loại <span class="book-edit-required">*</span></label>
                        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Chọn thể loại</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $book->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label for="image" class="form-label">Ảnh bìa</label>
                        <div class="book-edit-image-panel">
                            @if ($book->image)
                                @php
                                    $coverUrl = str_starts_with($book->image, 'books/')
                                        ? \Illuminate\Support\Facades\Storage::url($book->image)
                                        : asset($book->image);
                                @endphp
                                <img src="{{ $coverUrl }}" alt="Bìa {{ $book->title }}" class="book-edit-cover">
                            @else
                                <span class="book-edit-no-cover"><i class="bi bi-book"></i></span>
                            @endif
                            <div class="flex-grow-1 w-100">
                                <div class="fw-semibold mb-1">Ảnh hiện tại</div>
                                <input id="image" type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp">
                                <div class="form-text">Để trống nếu muốn giữ ảnh hiện tại. Chấp nhận JPG, JPEG, PNG hoặc WEBP, tối đa 2 MB.</div>
                                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="book-edit-actions">
                    <a href="{{ route('admin.books.index') }}" class="btn book-edit-back">Hủy</a>
                    <button type="submit" class="btn btn-lib-primary"><i class="bi bi-check2-circle me-1"></i>Cập nhật sách</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection