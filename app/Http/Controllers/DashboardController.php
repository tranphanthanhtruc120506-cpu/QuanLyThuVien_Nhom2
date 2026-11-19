<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $borrowingCount = BorrowRecord::where('status', 'borrowing')->count();
        $pendingCount = BorrowRecord::where('status', 'pending')->count();
        $overdueCount = BorrowRecord::where('status', 'borrowing')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString())
            ->count();
        $readerCount = User::where('role', 'user')->count();

        $lowStockBooks = Book::with(['author', 'category'])
            ->where('quantity', '<=', 2)
            ->orderBy('quantity')
            ->orderByDesc('id')
            ->get();

        return view('dashboard.index', compact(
            'totalBooks',
            'borrowingCount',
            'pendingCount',
            'overdueCount',
            'readerCount',
            'lowStockBooks'
        ));
    }
}