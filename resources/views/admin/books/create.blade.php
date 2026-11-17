@extends('layouts.app')

@section('title', 'Thêm sách')

@push('styles')
<style>
    .book-create-page {
        --form-wine: #821827;
        --form-wine-dark: #4d0d17;
        --form-gold: #c99d66;
        --form-border: #ede4d8;
        --form-muted: #786f70;
    }

    .book-create-hero {
        position: relative;
        overflow: hidden;
        padding: 1.75rem 2rem;
        border-radius: 18px;
        color: #fff;
        background: linear-gradient(125deg, var(--form-wine-dark), var(--form-wine) 72%, #9c2435);
        box-shadow: 0 12px 28px rgba(77, 13, 23, .15);
    }
    .book-create-hero::after {
        content: "";
        position: absolute;
        width: 200px;
        height: 200px;
        right: 7%;
        top: -75px;
        border: 1px solid rgba(255,255,255,.13);
        border-radius: 50%;
        box-shadow: 0 0 0 25px rgba(255,255,255,.035), 0 0 0 52px rgba(255,255,255,.025);
        pointer-events: none;
    }
    .book-create-hero-content { position: relative; z-index: 1; }
    .book-create-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .3rem .7rem;
        margin-bottom: .65rem;
        border-radius: 999px;
        color: #3d2b18;
        background: #e8d7be;
        font-size: .76rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .book-create-hero h1 {
        margin: 0 0 .35rem;
        color: #fff;
        font-family: var(--font-serif, Georgia, serif);
        font-size: clamp(1.7rem, 3vw, 2.25rem);
    }
    .book-create-hero p { margin: 0; color: rgba(255,255,255,.78); }
    .book-create-hero .btn { position: relative; z-index: 1; border-radius: 9px; white-space: nowrap; }

    .book-create-card {
        overflow: hidden;
        border: 1px solid var(--form-border);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 6px 20px rgba(54, 30, 30, .05);
    }
    .book-create-card-heading {
        padding: 1.15rem 1.4rem;
        border-bottom: 1px solid var(--form-border);
        background: #fcfaf7;
    }
    .book-create-card-heading h2 {
        margin: 0;
        color: #382b2d;
        font-family: var(--font-serif, Georgia, serif);
        font-size: 1.15rem;
    }
    .book-create-card-heading p { margin: .25rem 0 0; color: var(--form-muted); font-size: .88rem; }
    .book-create-card-body { padding: 1.5rem; }
    .book-create-page .form-label { margin-bottom: .45rem; color: #4a3a3c; font-weight: 700; font-size: .91rem; }
    .book-create-page .form-control,
    .book-create-page .form-select {
        min-height: 45px;
        border-color: #e7ded6;
        border-radius: 9px;
        background-color: #fff;
    }
    .book-create-page textarea.form-control { min-height: auto; }
    .book-create-page .form-control:focus,
    .book-create-page .form-select:focus {
        border-color: rgba(130,24,39,.5);
        box-shadow: 0 0 0 .2rem rgba(130,24,39,.1);
    }
    .book-create-page .form-text { color: var(--form-muted); font-size: .82rem; }
    .book-create-page .required-mark { color: #a63838; }
    .book-create-image-box {
        padding: 1rem;
        border: 1px dashed #d8c5b4;
        border-radius: 12px;
        background: #fcfaf7;
    }
    .book-create-image-box .image-icon {
        display: inline-flex;
        width: 42px;
        height: 42px;
        align-items: center;
        justify-content: center;
        margin-right: .75rem;
        border-radius: 11px;
        color: var(--form-wine);
        background: #f5e9e4;
        font-size: 1.2rem;
    }
    .book-create-actions {
        display: flex;
        justify-content: flex-end;
        gap: .65rem;
        padding-top: 1.2rem;
        margin-top: .5rem;
        border-top: 1px solid #f0e9e2;
    }
    .book-create-actions .btn { min-width: 125px; padding: .62rem 1rem; border-radius: 9px; }
    .book-create-back { color: var(--form-wine); border: 1px solid #e6cdd0; background: #fff; }
    .book-create-back:hover { color: var(--form-wine-dark); border-color: var(--form-wine); background: #fbf5f4; }

    @media (max-width: 575.98px) {
        .book-create-hero { padding: 1.35rem; }
        .book-create-hero-row { align-items: flex-start !important; flex-direction: column; }
        .book-create-card-body { padding: 1.1rem; }
        .book-create-actions { flex-direction: column-reverse; }
        .book-create-actions .btn { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="book-create-page">
    <section class="book-create-hero mb-4">
        <div class="book-create-hero-row d-flex justify-content-between align-items-center gap-4">
            <div class="book-create-hero-content">
                <span class="book-create-eyebrow"><i class="bi bi-plus-circle"></i> Kho sách thư viện</span>
                <h1>Thêm đầu sách mới</h1>
                <p>Điền thông tin sách để bổ sung vào danh mục thư viện.</p>
            </div>
            <a href="{{ route('admin.books.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left me-1"></i> Danh sách sách
            </a>
        </div>
    </section>

    <section class="book-create-card">
        <div class="book-create-card-heading">
            <h2><i class="bi bi-journal-plus me-2" style="color: var(--form-wine);"></i>Thông tin sách</h2>
            <p>Các mục có dấu <span class="required-mark">*</span> là bắt buộc.</p>
        </div>

        <div class="book-create-card-body">
            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-lg-8">
                        <label for="title" class="form-label">Tên sách <span class="required-mark">*</span></label>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}"
                            placeholder="Ví dụ: Cho tôi xin một vé đi tuổi thơ"
                            required
                        >
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-lg-4">
                        <label for="quantity" class="form-label">Số lượng <span class="required-mark">*</span></label>
                        <input
                            id="quantity"
                            type="number"
                            name="quantity"
                            class="form-control @error('quantity') is-invalid @enderror"
                            min="0"
                            value="{{ old('quantity', 0) }}"
                            required
                        >
                        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="author_id" class="form-label">Tác giả <span class="required-mark">*</span></label>
                        <select id="author_id" name="author_id" class="form-select @error('author_id') is-invalid @enderror" required>
                            <option value="">Chọn tác giả</option>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" @selected(old('author_id') == $author->id)>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('author_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Thể loại <span class="required-mark">*</span></label>
                        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Chọn thể loại</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="4"
                            placeholder="Giới thiệu ngắn về nội dung sách..."
                        >{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label for="image" class="form-label">Ảnh bìa</label>
                        <div class="book-create-image-box">
                            <div class="d-flex align-items-center mb-3">
                                <span class="image-icon"><i class="bi bi-image"></i></span>
                                <div>
                                    <div class="fw-semibold">Chọn ảnh bìa sách</div>
                                    <div class="form-text">JPG, JPEG, PNG hoặc WEBP; dung lượng tối đa 2 MB.</div>
                                </div>
                            </div>
                            <input
                                id="image"
                                type="file"
                                name="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp"
                            >
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="book-create-actions">
                    <a href="{{ route('admin.books.index') }}" class="btn book-create-back">Hủy</a>
                    <button type="submit" class="btn btn-lib-primary">
                        <i class="bi bi-check2-circle me-1"></i> Lưu sách
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection