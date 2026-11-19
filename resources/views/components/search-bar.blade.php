@props([
    'placeholder' => 'Tìm kiếm...',
    'name' => 'q',
    'value' => request('q', ''),
])

<form method="GET" action="{{ request()->url() }}" class="d-flex gap-2">
    {{-- Giữ lại các tham số lọc khác nếu đang có (ví dụ category trên trang catalog) --}}
    @foreach (request()->except([$name, 'page']) as $key => $val)
        @if (!is_array($val))
            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
        @endif
    @endforeach

    <input
        type="text"
        name="{{ $name }}"
        value="{{ $value }}"
        class="form-control"
        placeholder="{{ $placeholder }}"
        aria-label="{{ $placeholder }}"
    >

    <button type="submit" class="btn btn-primary text-nowrap">
        Tìm
    </button>

    @if (request()->filled($name))
        <a href="{{ request()->url() }}" class="btn btn-outline-secondary text-nowrap">
            Xóa lọc
        </a>
    @endif
</form>