<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ReaderController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->where('role', 'user');

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function toggle(User $user)
    {
        // Không cho khóa/mở chính mình hoặc tài khoản admin
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Không thể tự khóa hoặc thay đổi trạng thái của chính mình.');
        }

        if ($user->role === 'admin') {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Không thể khóa hoặc mở tài khoản admin.');
        }

        $user->is_active = ! (bool) $user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'mở khóa' : 'khóa';

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Đã {$statusText} tài khoản {$user->email}.");
    }
}