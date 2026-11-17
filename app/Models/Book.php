<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author_id',
        'category_id',
        'quantity',
        'description',
        'image',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }

    // Đường dẫn ảnh bìa dùng chung cho mọi view.
    // Ảnh upload từ admin: "books/xxx.jpg" (storage), ảnh seed: "images/books/xxx.png" (public).
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return str_starts_with($this->image, 'books/')
            ? asset('storage/' . $this->image)
            : asset($this->image);
    }

    public function isAvailable(): bool
    {
        return $this->quantity > 0;
    }
}