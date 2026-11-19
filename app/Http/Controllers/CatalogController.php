<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $query = Book::query()->with(['author', 'category']);

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhereHas('author', function ($authorQuery) use ($q) {
                        $authorQuery->where('name', 'like', "%{$q}%");
                    });
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $books = $query->orderByDesc('id')->paginate(12)->withQueryString();

        return view('catalog.index', compact('books', 'categories'));
    }
}