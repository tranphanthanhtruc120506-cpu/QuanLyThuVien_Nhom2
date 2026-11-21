<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookRecommendationController;
use App\Http\Controllers\BorrowRecordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReaderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AuthorController as AdminAuthorController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes - Hệ Thống Quản Lý Thư Viện (SSR)
|--------------------------------------------------------------------------
*/

// Trang chủ tra cứu kho sách thư viện
Route::get('/', [BookController::class, 'index'])->name('books.index');
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// Gợi ý sách theo tâm trạng/mục tiêu: khách có thể xem, mượn sách vẫn cần đăng nhập.
Route::get('/recommendations', [BookRecommendationController::class, 'index'])->name('recommendations.index');
Route::post('/recommendations', [BookRecommendationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('recommendations.store');

// Điều hướng trang Dashboard thông minh theo Role
Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('books.index');
})->middleware(['auth'])->name('dashboard');

// Nhóm tính năng dành cho người dùng đã đăng nhập (Độc giả & Quản trị)
Route::middleware('auth')->group(function () {
    // Mượn sách
    Route::post('/books/{book}/borrow', [BookController::class, 'borrow'])->name('books.borrow');

    // Lịch sử mượn trả của độc giả
    Route::get('/my-borrows', [BorrowRecordController::class, 'index'])->name('borrows.index');
    Route::delete('/my-borrows/{borrowRecord}', [BorrowRecordController::class, 'cancel'])->name('borrows.cancel');

    // Quản lý hồ sơ cá nhân
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Nhóm chức năng Quản trị thư viện (Chỉ dành cho role: admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/borrows', [AdminController::class, 'borrows'])->name('borrows');
    Route::post('/borrows/{borrowRecord}/approve', [AdminController::class, 'approve'])->name('borrows.approve');
    Route::post('/borrows/{borrowRecord}/reject', [AdminController::class, 'reject'])->name('borrows.reject');
    Route::post('/borrows/{borrowRecord}/return', [AdminController::class, 'returnBook'])->name('borrows.return');

    // Quản lý sách và tác giả (Hào)
    Route::resource('books', AdminBookController::class)->except('show');
    Route::resource('authors', AdminAuthorController::class)->except('show');

    // Hoàn thiện các module đã có controller/view nhưng chưa khai báo route.
    Route::resource('categories', CategoryController::class)->except('show');
    Route::get('users', [ReaderController::class, 'index'])->name('users.index');
    Route::patch('users/{user}/toggle', [ReaderController::class, 'toggle'])->name('users.toggle');
});

require __DIR__.'/auth.php';