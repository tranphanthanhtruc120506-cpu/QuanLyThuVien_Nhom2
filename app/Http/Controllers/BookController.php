<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BookController extends Controller
{
    /**
     * Danh sách sách trong thư viện (Tra cứu, Lọc theo thể loại, Tìm kiếm + AJAX)
     */
    public function index(Request $request): View
    {
        $query = Book::with(['author', 'category']);

        // Tìm kiếm theo từ khóa (tiêu đề sách hoặc tên tác giả)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('author', function ($authorQ) use ($search) {
                      $authorQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Lọc theo thể loại sách
        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        // Lọc sách còn sẵn trong kho
        if ($request->boolean('available_only')) {
            $query->where('quantity', '>', 0);
        }

        $books = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::withCount('books')->get();

        // Nếu là yêu cầu AJAX thì chỉ trả về phần HTML danh sách sách
        if ($request->ajax()) {
            return view('books.partials.book-list', compact('books'));
        }

        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Chi tiết sách
     */
    public function show(Book $book): View
    {
        $book->load(['author', 'category']);

        $userHasPendingOrBorrowing = false;
        if (auth()->check()) {
            $userHasPendingOrBorrowing = BorrowRecord::where('user_id', auth()->id())
                ->where('book_id', $book->id)
                ->whereIn('status', [BorrowRecord::PENDING, BorrowRecord::BORROWING])
                ->exists();
        }

        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();

        return view('books.show', compact('book', 'userHasPendingOrBorrowing', 'relatedBooks'));
    }

    /**
     * Xử lý yêu cầu mượn sách từ độc giả
     */
    public function borrow(Request $request, Book $book): RedirectResponse
    {
        $user = $request->user();

        // 1. Kiểm tra độc giả có bị khóa tài khoản không
        if (!$user->is_active) {
            return back()->with('error', 'Tài khoản của bạn đang bị khóa, không thể thực hiện mượn sách.');
        }

        // 2. Kiểm tra số lượng tồn kho
        if ($book->quantity <= 0) {
            return back()->with('error', 'Rất tiếc, đầu sách này hiện đã hết trong kho thư viện.');
        }

        // 3. Kiểm tra xem độc giả đã mượn hoặc đang chờ duyệt cuốn sách này chưa
        $existing = BorrowRecord::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereIn('status', [BorrowRecord::PENDING, BorrowRecord::BORROWING])
            ->first();

        if ($existing) {
            $msg = $existing->status === BorrowRecord::PENDING
                ? 'Bạn đã có một yêu cầu mượn cuốn sách này đang chờ thủ thư phê duyệt.'
                : 'Bạn đang mượn cuốn sách này, vui lòng hoàn trả trước khi mượn tiếp.';
            return back()->with('error', $msg);
        }

        // 4. Kiểm tra giới hạn tối đa 3 phiếu đang xử lý (pending + borrowing)
        $activeCount = BorrowRecord::where('user_id', $user->id)
            ->whereIn('status', [BorrowRecord::PENDING, BorrowRecord::BORROWING])
            ->count();

        if ($activeCount >= 3) {
            return back()->with('error', 'Bạn chỉ được mượn hoặc đặt tối đa 3 cuốn sách cùng lúc.');
        }

        // Tạo bản ghi mượn sách mới (trạng thái pending)
        BorrowRecord::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status'  => BorrowRecord::PENDING,
        ]);

        return redirect()->route('borrows.index')
            ->with('success', 'Yêu cầu mượn sách "' . $book->title . '" đã được gửi thành công. Vui lòng chờ thủ thư phê duyệt!');
    }
}
