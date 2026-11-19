@if($records->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Mã Phiếu</th>
                    <th>Độc Giả</th>
                    <th>Sách Mượn</th>
                    <th>Ngày Mượn</th>
                    <th>Hạn Trả</th>
                    <th>Trạng Thái</th>
                    <th class="text-end pe-4">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $rec)
                    <tr>
                        <td class="ps-4 fw-bold text-muted">#{{ str_pad($rec->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="fw-bold">{{ $rec->user->name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $rec->user->email ?? '' }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold text-truncate" style="max-width: 220px;" title="{{ $rec->book->title ?? '' }}">
                                {{ $rec->book->title ?? 'N/A' }}
                            </div>
                            <small class="text-muted">Kho: {{ $rec->book->quantity ?? 0 }}</small>
                        </td>
                        <td>{{ $rec->borrow_date ? $rec->borrow_date->format('d/m/Y') : '—' }}</td>
                        <td>
                            @if($rec->due_date)
                                <span class="{{ $rec->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                    {{ $rec->due_date->format('d/m/Y') }}
                                </span>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($rec->status === \App\Models\BorrowRecord::PENDING)
                                <span class="badge-status badge-pending"><i class="bi bi-hourglass-split"></i> Chờ duyệt</span>
                            @elseif($rec->status === \App\Models\BorrowRecord::BORROWING)
                                @if($rec->isOverdue())
                                    <span class="badge-status badge-overdue"><i class="bi bi-exclamation-octagon"></i> Quá hạn</span>
                                @else
                                    <span class="badge-status badge-borrowing"><i class="bi bi-book-half"></i> Đang mượn</span>
                                @endif
                            @elseif($rec->status === \App\Models\BorrowRecord::RETURNED)
                                <span class="badge-status badge-returned"><i class="bi bi-check2-circle"></i> Đã trả ({{ $rec->return_date ? $rec->return_date->format('d/m') : '' }})</span>
                            @elseif($rec->status === \App\Models\BorrowRecord::REJECTED)
                                <span class="badge-status badge-rejected"><i class="bi bi-x-circle"></i> Từ chối</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if($rec->status === \App\Models\BorrowRecord::PENDING)
                                <form method="POST" action="{{ route('admin.borrows.approve', $rec) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-2" title="Duyệt cho mượn">
                                        <i class="bi bi-check-lg"></i> Duyệt
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.borrows.reject', $rec) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2" title="Từ chối">
                                        <i class="bi bi-x-lg"></i> Từ chối
                                    </button>
                                </form>
                            @elseif($rec->status === \App\Models\BorrowRecord::BORROWING)
                                <form method="POST" action="{{ route('admin.borrows.return', $rec) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3"
                                            onclick="return confirm('Xác nhận độc giả đã trả sách và đưa sách về lại kho?')">
                                        <i class="bi bi-arrow-return-left me-1"></i> Nhận lại sách
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">Hoàn tất</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top d-flex justify-content-center">
        {{ $records->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
        <h5 class="font-serif fw-bold">Không tìm thấy phiếu mượn nào</h5>
        <p class="text-muted mb-0">Thử thay đổi từ khóa hoặc bộ lọc trạng thái để xem kết quả.</p>
    </div>
@endif