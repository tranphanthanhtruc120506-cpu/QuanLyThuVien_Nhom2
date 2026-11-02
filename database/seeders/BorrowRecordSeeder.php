<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\User;
use Illuminate\Database\Seeder;

class BorrowRecordSeeder extends Seeder
{
    public function run(): void
    {
        $u = User::where('role', 'user')->where('is_active', true)->orderBy('id')->get();
        $b = Book::orderBy('id')->get();
        $today = now();

        $records = [
            // user, sách, trạng thái, mượn cách đây (ngày), hạn trả cách đây (số âm = hạn còn ở tương lai)
            [$u[0], $b[0], 'borrowing', 5,   -9],    // đang mượn, còn hạn
            [$u[0], $b[6], 'borrowing', 20,   6],    // QUÁ HẠN 6 ngày
            [$u[1], $b[3], 'borrowing', 25,  11],    // QUÁ HẠN 11 ngày
            [$u[1], $b[8], 'returned',  30,  16],    // đã trả
            [$u[2], $b[5], 'returned',  40,  26],    // đã trả
            [$u[2], $b[1], 'pending',   null, null], // chờ duyệt
            [$u[3], $b[6], 'pending',   null, null], // chờ duyệt
            [$u[3], $b[2], 'rejected',  null, null], // bị từ chối
        ];

        foreach ($records as [$user, $book, $status, $ago, $dueAgo]) {
            $borrow = $ago !== null ? $today->copy()->subDays($ago) : null;
            $due    = $dueAgo !== null ? $today->copy()->subDays($dueAgo) : null;

            BorrowRecord::create([
                'user_id'     => $user->id,
                'book_id'     => $book->id,
                'borrow_date' => $borrow,
                'due_date'    => $due,
                'return_date' => $status === 'returned' ? $due?->copy()->subDays(2) : null,
                'status'      => $status,
            ]);

            // Sách đang mượn thì trừ tồn kho cho khớp nghiệp vụ
            if ($status === 'borrowing') {
                $book->decrement('quantity');
            }
        }
    }
}