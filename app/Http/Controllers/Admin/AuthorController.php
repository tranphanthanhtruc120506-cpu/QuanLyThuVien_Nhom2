<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthorController extends Controller
{
    // Danh sách, tìm kiếm và phân trang tác giả.
    public function index(Request $request): View
    {
        $query = Author::withCount('books');
        $search = trim((string) $request->query('search'));

        if ($search !== '') {
            $query->where(function ($authorQuery) use ($search) {
                $authorQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        $authors = $query->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.authors.index', compact('authors'));
    }

    // Mở form thêm tác giả.
    public function create(): View
    {
        return view('admin.authors.create');
    }

    // Kiểm tra và lưu tác giả mới.
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ]);

        Author::create($data);

        return redirect()
            ->route('admin.authors.index')
            ->with('success', 'Đã thêm tác giả thành công.');
    }

    // Mở form sửa tác giả.
    public function edit(Author $author): View
    {
        return view('admin.authors.edit', compact('author'));
    }

    // Kiểm tra và cập nhật tác giả.
    public function update(Request $request, Author $author): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ]);

        $author->update($data);

        return redirect()
            ->route('admin.authors.index')
            ->with('success', 'Đã cập nhật tác giả thành công.');
    }

    // Không xóa tác giả đang được sách sử dụng.
    public function destroy(Author $author): RedirectResponse
    {
        if ($author->books()->exists()) {
            return redirect()
                ->route('admin.authors.index')
                ->with('error', 'Không thể xóa tác giả đang có sách trong thư viện.');
        }

        $author->delete();

        return redirect()
            ->route('admin.authors.index')
            ->with('success', 'Đã xóa tác giả thành công.');
    }
}