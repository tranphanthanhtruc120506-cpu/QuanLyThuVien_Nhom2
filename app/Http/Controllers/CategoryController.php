<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query()->withCount('books');

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where('name', 'like', "%{$q}%");
        }

        $categories = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ], [
            'name.required' => 'Vui lòng nhập tên thể loại.',
            'name.unique' => 'Tên thể loại này đã tồn tại.',
            'name.max' => 'Tên thể loại tối đa 255 ký tự.',
        ]);

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Thêm thể loại thành công.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
        ], [
            'name.required' => 'Vui lòng nhập tên thể loại.',
            'name.unique' => 'Tên thể loại này đã tồn tại.',
            'name.max' => 'Tên thể loại tối đa 255 ký tự.',
        ]);

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Cập nhật thể loại thành công.');
    }

    public function destroy(Category $category)
    {
        // Kiểm tra xem thể loại có sách nào không trước khi xóa
        $hasBooks = method_exists($category, 'books')
            ? $category->books()->exists()
            : \App\Models\Book::where('category_id', $category->id)->exists();

        if ($hasBooks) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Không thể xóa thể loại đang có sách.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Xóa thể loại thành công.');
    }
}