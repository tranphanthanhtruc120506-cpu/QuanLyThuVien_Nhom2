@extends('layouts.app')

@section('title', 'Gợi Ý Sách Theo Tâm Trạng - Library System')

@section('content')
<div class="p-4 p-md-5 mb-4 rounded-4 text-white shadow-sm"
     style="background: linear-gradient(135deg, #4d0d17 0%, #821827 70%, #9c2435 100%);">
    <span class="badge rounded-pill bg-warning text-dark mb-3"><i class="bi bi-stars me-1"></i> Khám phá sách phù hợp</span>
    <h1 class="font-serif display-6 fw-bold mb-2">Hôm nay bạn muốn đọc gì?</h1>
    <p class="mb-0 text-white-50">Chọn tâm trạng và mục tiêu đọc, thư viện sẽ tìm tối đa 3 đầu sách còn trong kho dành cho bạn.</p>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="bg-white rounded-4 border shadow-sm p-4">
            <h2 class="font-serif h4 fw-bold mb-3">Nói một chút về bạn</h2>
            <form action="{{ route('recommendations.store') }}" method="POST" id="recommendation-form">
                @csrf

                <div class="mb-3">
                    <label for="mood" class="form-label fw-semibold">Tâm trạng hiện tại <span class="text-danger">*</span></label>
                    <select name="mood" id="mood" class="form-select @error('mood') is-invalid @enderror" required>
                        <option value="">-- Chọn tâm trạng --</option>
                        <option value="stressed" @selected(($selection['mood'] ?? old('mood')) === 'stressed')>Căng thẳng, mệt mỏi</option>
                        <option value="sad" @selected(($selection['mood'] ?? old('mood')) === 'sad')>Hơi buồn, muốn nhẹ lòng</option>
                        <option value="curious" @selected(($selection['mood'] ?? old('mood')) === 'curious')>Tò mò, muốn khám phá</option>
                        <option value="motivated" @selected(($selection['mood'] ?? old('mood')) === 'motivated')>Có động lực, muốn phát triển</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="goal" class="form-label fw-semibold">Bạn muốn đọc để làm gì? <span class="text-danger">*</span></label>
                    <select name="goal" id="goal" class="form-select @error('goal') is-invalid @enderror" required>
                        <option value="">-- Chọn mục tiêu --</option>
                        <option value="relax" @selected(($selection['goal'] ?? old('goal')) === 'relax')>Thư giãn sau giờ học</option>
                        <option value="learn" @selected(($selection['goal'] ?? old('goal')) === 'learn')>Học hỏi kiến thức mới</option>
                        <option value="inspire" @selected(($selection['goal'] ?? old('goal')) === 'inspire')>Tìm cảm hứng, động lực</option>
                        <option value="enjoy" @selected(($selection['goal'] ?? old('goal')) === 'enjoy')>Giải trí, đọc cho vui</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label fw-semibold">Bạn có sở thích gì đặc biệt? <span class="text-muted fw-normal">(không bắt buộc)</span></label>
                    <textarea id="note" name="note" rows="3" maxlength="280"
                              class="form-control @error('note') is-invalid @enderror"
                              placeholder="Ví dụ: Tôi thích sách gần gũi, dễ đọc, không quá dài...">{{ $selection['note'] ?? old('note') }}</textarea>
                    <div class="form-text">Tối đa 280 ký tự. Không nhập thông tin cá nhân hoặc dữ liệu nhạy cảm.</div>
                </div>

                <button type="submit" id="recommendation-submit" class="btn btn-lib-primary rounded-pill px-4 py-2 w-100">
                    <i class="bi bi-magic me-1"></i> <span id="recommendation-button-label">Tìm sách phù hợp</span>
                </button>
                <p class="small text-muted mt-3 mb-0">
                    Nếu cấu hình Gemini đang bật, các lựa chọn và mô tả bạn nhập sẽ được gửi đến Google
                    cùng thông tin sách trong thư viện để xếp hạng. Hệ thống vẫn gợi ý được khi AI tạm ngừng.
                </p>
            </form>
        </div>
    </div>

    <div class="col-lg-7">
        @if($results === null)
            <div class="bg-white rounded-4 border shadow-sm p-4 p-md-5 text-center h-100 d-flex flex-column justify-content-center align-items-center">
                <i class="bi bi-book-half display-2 mb-3" style="color: #821827;"></i>
                <h2 class="font-serif h4 fw-bold">Chưa biết bắt đầu với cuốn nào?</h2>
                <p class="text-muted mb-0">Điền hai lựa chọn bên trái, danh sách gợi ý sẽ xuất hiện tại đây.</p>
            </div>
        @elseif(empty($results))
            <div class="alert alert-info rounded-4 p-4">
                <i class="bi bi-info-circle me-1"></i> Hiện thư viện chưa có sách còn sẵn để gợi ý.
                <a href="{{ route('books.index') }}" class="alert-link">Xem kho sách</a>.
            </div>
        @else
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h2 class="font-serif h4 fw-bold mb-0">Sách dành cho bạn</h2>
                @if($source === 'ai')
                    <span class="badge bg-success rounded-pill">Gemini hỗ trợ xếp hạng</span>
                @elseif($source === 'mixed')
                    <span class="badge bg-secondary rounded-pill">AI + gợi ý nội bộ</span>
                @else
                    <span class="badge bg-secondary rounded-pill">Gợi ý từ thư viện</span>
                @endif
            </div>

            <div class="d-flex flex-column gap-3">
                @foreach($results as $result)
                    @php($book = $result['book'])
                    <div class="bg-white rounded-4 border shadow-sm p-3 p-md-4">
                        <div class="row g-3 align-items-center">
                            <div class="col-3 col-md-2 text-center">
                                @if($book->image_url)
                                    <img src="{{ $book->image_url }}" alt="Bìa sách {{ $book->title }}"
                                         class="img-fluid rounded-3 shadow-sm" style="max-height: 150px; object-fit: contain;">
                                @else
                                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                                         style="min-height: 120px; background:#821827;">
                                        <i class="bi bi-book fs-1"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-9 col-md-10">
                                <div class="small text-muted mb-1">Gợi ý #{{ $loop->iteration }} · {{ $book->category?->name ?? 'Chưa phân loại' }}</div>
                                <h3 class="font-serif h5 fw-bold mb-1">{{ $book->title }}</h3>
                                <p class="small text-muted mb-2">{{ $book->author?->name ?? 'Chưa rõ tác giả' }}</p>
                                <p class="mb-2">{{ $result['reason'] }}</p>
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <span class="small text-success"><i class="bi bi-check-circle me-1"></i> Còn {{ $book->quantity }} cuốn</span>
                                    <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-lib-primary rounded-pill px-3">
                                        Xem chi tiết &amp; mượn <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="small text-muted mt-3 mb-0">Gợi ý chỉ mang tính tham khảo. Số lượng sách được kiểm tra khi gửi yêu cầu mượn.</p>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('recommendation-form')?.addEventListener('submit', function () {
    const button = document.getElementById('recommendation-submit');
    if (button && this.checkValidity()) {
        button.disabled = true;
        document.getElementById('recommendation-button-label').textContent = 'Đang tìm sách phù hợp...';
    }
});
</script>
@endpush
