<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    /**
     * Tổng quan trang quản trị thư viện
     */
    public function dashboard(): View
    {
        $stats = [
            'total_books'     => Book::count(),
            'total_borrowing' => BorrowRecord::where('status', BorrowRecord::BORROWING)->count(),
            'total_pending'   => BorrowRecord::where('status', BorrowRecord::PENDING)->count(),
            'total_users'     => User::where('role', 'user')->count(),
            'total_overdue'   => BorrowRecord::overdue()->count(),
            'total_returned'  => BorrowRecord::where('status', BorrowRecord::RETURNED)->count(),
        ];

        // 5 yêu cầu mượn sách mới nhất đang chờ duyệt
        $pendingRecords = BorrowRecord::with(['user', 'book'])
            ->where('status', BorrowRecord::PENDING)
            ->latest()
            ->take(5)
            ->get();

        // 5 lượt mượn quá hạn cần nhắc nhở
        $overdueRecords = BorrowRecord::with(['user', 'book'])
            ->overdue()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingRecords', 'overdueRecords'));
    }

    /**
     * Quản lý toàn bộ danh sách phiếu mượn (Hỗ trợ cả tải trang thường và AJAX)
     */
    public function borrows(Request $request): View
    {
        $query = BorrowRecord::with(['user', 'book']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uQ) use ($search) {
                    $uQ->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('book', function ($bQ) use ($search) {
                    $bQ->where('title', 'like', "%{$search}%");
                });
            });
        }

        $status = $request->input('status');
        if ($status && in_array($status, [BorrowRecord::PENDING, BorrowRecord::BORROWING, BorrowRecord::RETURNED, BorrowRecord::REJECTED])) {
            $query->where('status', $status);
        } elseif ($status === 'overdue') {
            $query->overdue();
        }

        $records = $query->latest()->paginate(15)->withQueryString();

        // Nếu là yêu cầu AJAX thì chỉ trả về phần HTML của bảng dữ liệu
        if ($request->ajax()) {
            return view('admin.partials.borrows-table', compact('records'));
        }

        return view('admin.borrows', compact('records', 'status'));
    }
    /**
     * Duyệt yêu cầu mượn sách (Pending -> Borrowing)
     */
    public function approve(BorrowRecord $borrowRecord): RedirectResponse
    {
        try {
            DB::transaction(function () use ($borrowRecord) {
                $record = BorrowRecord::where('id', $borrowRecord->id)->lockForUpdate()->first();
                $book   = Book::where('id', $record->book_id)->lockForUpdate()->first();

                if ($record->status !== BorrowRecord::PENDING) {
                    throw new \Exception('Phiếu mượn không ở trạng thái chờ duyệt hoặc đã được xử lý.');
                }

                if ($book->quantity <= 0) {
                    throw new \Exception('Sách này trong kho đã hết, không thể duyệt mượn.');
                }

                // Giảm số lượng tồn kho của sách đi 1
                $book->decrement('quantity');

                // Cập nhật ngày mượn và hạn trả (mặc định 14 ngày)
                $record->update([
                    'status'      => BorrowRecord::BORROWING,
                    'borrow_date' => now()->toDateString(),
                    'due_date'    => now()->addDays(14)->toDateString(),
                ]);
            });

            return back()->with('success', 'Đã duyệt yêu cầu mượn sách thành công! Hạn trả: ' . now()->addDays(14)->format('d/m/Y'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Từ chối yêu cầu mượn sách (Pending -> Rejected)
     */
    public function reject(BorrowRecord $borrowRecord): RedirectResponse
    {
        if ($borrowRecord->status !== BorrowRecord::PENDING) {
            return back()->with('error', 'Phiếu mượn không ở trạng thái chờ duyệt.');
        }

        $borrowRecord->update([
            'status' => BorrowRecord::REJECTED,
        ]);

        return back()->with('success', 'Đã từ chối yêu cầu mượn sách.');
    }

    /**
     * Xác nhận độc giả đã trả sách (Borrowing -> Returned)
     */
    public function returnBook(BorrowRecord $borrowRecord): RedirectResponse
    {
        try {
            DB::transaction(function () use ($borrowRecord) {
                $record = BorrowRecord::where('id', $borrowRecord->id)->lockForUpdate()->first();
                $book   = Book::where('id', $record->book_id)->lockForUpdate()->first();

                if ($record->status !== BorrowRecord::BORROWING) {
                    throw new \Exception('Phiếu mượn này không ở trạng thái đang mượn hoặc đã được trả.');
                }

                // Tăng lại số lượng sách trong kho
                $book->increment('quantity');

                // Ghi nhận ngày trả sách
                $record->update([
                    'status'      => BorrowRecord::RETURNED,
                    'return_date' => now()->toDateString(),
                ]);
            });

            return back()->with('success', 'Đã xác nhận trả sách thành công và cập nhật lại kho!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
