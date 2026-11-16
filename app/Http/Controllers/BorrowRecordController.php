<?php

namespace App\Http\Controllers;

use App\Models\BorrowRecord;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BorrowRecordController extends Controller
{
    /**
     * Danh sách phiếu mượn của độc giả hiện tại
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = BorrowRecord::where('user_id', $user->id)
            ->with(['book.author', 'book.category']);

        // Lọc theo trạng thái nếu có
        $status = $request->input('status');
        if ($status && in_array($status, [BorrowRecord::PENDING, BorrowRecord::BORROWING, BorrowRecord::RETURNED, BorrowRecord::REJECTED])) {
            $query->where('status', $status);
        } elseif ($status === 'overdue') {
            $query->overdue();
        }

        $records = $query->latest()->paginate(10)->withQueryString();

        // Thống kê nhanh của độc giả
        $stats = [
            'total'     => BorrowRecord::where('user_id', $user->id)->count(),
            'pending'   => BorrowRecord::where('user_id', $user->id)->where('status', BorrowRecord::PENDING)->count(),
            'borrowing' => BorrowRecord::where('user_id', $user->id)->where('status', BorrowRecord::BORROWING)->count(),
            'returned'  => BorrowRecord::where('user_id', $user->id)->where('status', BorrowRecord::RETURNED)->count(),
            'overdue'   => BorrowRecord::where('user_id', $user->id)->overdue()->count(),
        ];

        return view('borrows.index', compact('records', 'stats', 'status'));
    }

    /**
     * Hủy yêu cầu mượn khi còn ở trạng thái pending
     */
    public function cancel(Request $request, BorrowRecord $borrowRecord): RedirectResponse
    {
        $user = $request->user();

        if ($borrowRecord->user_id !== $user->id) {
            abort(403);
        }

        if ($borrowRecord->status !== BorrowRecord::PENDING) {
            return back()->with('error', 'Chỉ có thể hủy yêu cầu mượn khi đang ở trạng thái chờ duyệt.');
        }

        $borrowRecord->delete();

        return back()->with('success', 'Đã hủy yêu cầu mượn sách thành công.');
    }
}
