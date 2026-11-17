@extends('layouts.app')

@section('title', 'Thêm tác giả')

@section('content')
<div class="author-form-page">
    <section class="author-form-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1>Thêm tác giả mới</h1>
                <p>Bổ sung hồ sơ tác giả vào danh mục thư viện.</p>
            </div>
            <a href="{{ route('admin.authors.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i> Danh sách tác giả</a>
        </div>
    </section>
    @include('admin.authors._form', ['author' => null])
</div>
@endsection