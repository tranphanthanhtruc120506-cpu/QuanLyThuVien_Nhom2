<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRecord extends Model
{
    public const PENDING   = 'pending';
    public const BORROWING = 'borrowing';
    public const RETURNED  = 'returned';
    public const REJECTED  = 'rejected';

    protected $fillable = [
        'user_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date'    => 'date',
        'return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Quá hạn = đang mượn và đã qua hạn trả
    public function scopeOverdue($query)
    {
        return $query->where('status', self::BORROWING)
                     ->whereDate('due_date', '<', now()->toDateString());
    }

    public function isOverdue(): bool
    {
        return $this->status === self::BORROWING
            && $this->due_date
            && $this->due_date->isPast()
            && ! $this->due_date->isToday();
    }
}