<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowRecordController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReaderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| QUY TẮC
|--------------------------------------------------------------------------
| - Mỗi người CHỈ viết route trong khối của mình.
| - Chỉ bỏ dấu // khi controller đã tồn tại, nếu không web sẽ báo lỗi.
| - Pull trước khi sửa file này, push ngay sau khi sửa.
| - Đặt tên route: admin.books.index, books.show, borrows.mine ...
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


// =====================================================================
// VANH: Đăng nhập, đăng ký, đăng xuất, hồ sơ, trang chủ
// =====================================================================
// require __DIR__.'/auth.php';   // file do Breeze tạo
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
// });


// =====================================================================
// HÀO: Sách và Tác giả
// =====================================================================
// Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::resource('books', BookController::class)->except('show');
//     Route::resource('authors', AuthorController::class)->except('show');
// });
// Trang chi tiết sách dùng chung admin và user
// Route::middleware('auth')->get('/books/{book}', [BookController::class, 'show'])->name('books.show');


// =====================================================================
// ĐẠT: Mượn và Trả sách
// =====================================================================
// Khu user
// Route::middleware('auth')->group(function () {
//     Route::post('/books/{book}/borrow', [BorrowRecordController::class, 'store'])->name('borrows.store');
//     Route::get('/my-borrows', [BorrowRecordController::class, 'mine'])->name('borrows.mine');
//     Route::delete('/my-borrows/{borrowRecord}', [BorrowRecordController::class, 'cancel'])->name('borrows.cancel');
// });
// Khu admin
// Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/borrows', [BorrowRecordController::class, 'index'])->name('borrows.index');
//     Route::patch('/borrows/{borrowRecord}/approve', [BorrowRecordController::class, 'approve'])->name('borrows.approve');
//     Route::patch('/borrows/{borrowRecord}/reject', [BorrowRecordController::class, 'reject'])->name('borrows.reject');
//     Route::patch('/borrows/{borrowRecord}/return', [BorrowRecordController::class, 'markReturned'])->name('borrows.return');
// });


// =====================================================================
// NA: Độc giả, Thể loại, Catalog, Dashboard
// =====================================================================
// Catalog sách cho user
// Route::middleware('auth')->get('/books', [CatalogController::class, 'index'])->name('books.index');
// Khu admin
// Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//     Route::resource('categories', CategoryController::class)->except('show');
//     Route::get('/users', [ReaderController::class, 'index'])->name('users.index');
//     Route::patch('/users/{user}/toggle', [ReaderController::class, 'toggle'])->name('users.toggle');
// });