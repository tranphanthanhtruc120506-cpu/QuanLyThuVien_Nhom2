@once
    @push('styles')
    <style>
        .author-form-page { --author-form-wine: #821827; --author-form-dark: #4d0d17; --author-form-border: #ede4d8; }
        .author-form-hero { position: relative; overflow: hidden; padding: 1.7rem 2rem; border-radius: 18px; color: #fff; background: linear-gradient(125deg, var(--author-form-dark), var(--author-form-wine) 72%, #9c2435); box-shadow: 0 12px 28px rgba(77,13,23,.15); }
        .author-form-hero h1 { margin: 0 0 .35rem; color: #fff; font-family: var(--font-serif, Georgia, serif); font-size: clamp(1.7rem, 3vw, 2.2rem); }
        .author-form-hero p { margin: 0; color: rgba(255,255,255,.78); }
        .author-form-card { overflow: hidden; border: 1px solid var(--author-form-border); border-radius: 16px; background: #fff; box-shadow: 0 6px 20px rgba(54,30,30,.05); }
        .author-form-card-head { padding: 1.1rem 1.4rem; border-bottom: 1px solid var(--author-form-border); background: #fcfaf7; }
        .author-form-card-head h2 { margin: 0; color: #382b2d; font-family: var(--font-serif, Georgia, serif); font-size: 1.15rem; }
        .author-form-card-body { padding: 1.5rem; }
        .author-form-card .form-label { color: #4a3a3c; font-weight: 700; font-size: .91rem; }
        .author-form-card .form-control { border-color: #e7ded6; border-radius: 9px; }
        .author-form-card .form-control:focus { border-color: rgba(130,24,39,.5); box-shadow: 0 0 0 .2rem rgba(130,24,39,.1); }
        .author-form-actions { display: flex; justify-content: flex-end; gap: .65rem; padding-top: 1.1rem; margin-top: .5rem; border-top: 1px solid #f0e9e2; }
        .author-form-actions .btn { min-width: 125px; padding: .62rem 1rem; border-radius: 9px; }
        .author-form-back { color: var(--author-form-wine); border: 1px solid #e6cdd0; background: #fff; }
        .author-form-back:hover { color: var(--author-form-dark); border-color: var(--author-form-wine); background: #fbf5f4; }
        @media (max-width: 575.98px) { .author-form-hero { padding: 1.35rem; } .author-form-card-body { padding: 1.1rem; } .author-form-actions { flex-direction: column-reverse; } .author-form-actions .btn { width: 100%; } }
    </style>
    @endpush
@endonce

<div class="author-form-card">
    <div class="author-form-card-head">
        <h2><i class="bi bi-person-vcard me-2" style="color:#821827"></i>Thông tin tác giả</h2>
    </div>
    <div class="author-form-card-body">
        <form action="{{ $author ? route('admin.authors.update', $author) : route('admin.authors.store') }}" method="POST">
            @csrf
            @if ($author)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Tên tác giả <span class="text-danger">*</span></label>
                <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $author->name ?? '') }}" placeholder="Nhập tên tác giả" required maxlength="255">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="bio" class="form-label">Giới thiệu</label>
                <textarea id="bio" name="bio" class="form-control @error('bio') is-invalid @enderror" rows="5" placeholder="Thông tin ngắn về tác giả (không bắt buộc)">{{ old('bio', $author->bio ?? '') }}</textarea>
                @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="author-form-actions">
                <a href="{{ route('admin.authors.index') }}" class="btn author-form-back">Hủy</a>
                <button type="submit" class="btn btn-lib-primary">
                    <i class="bi bi-check2-circle me-1"></i>{{ $author ? 'Lưu thay đổi' : 'Thêm tác giả' }}
                </button>
            </div>
        </form>
    </div>
</div>