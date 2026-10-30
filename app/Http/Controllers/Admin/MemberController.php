<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $role = $request->input('role');
        $search = $request->input('q');

        $query = User::withCount(['loans', 'reviews'])->latest();

        if ($role) {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('member_number', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.members.index', compact('users', 'role', 'search'));
    }

    public function show(User $user): View
    {
        $user->load(['loans.book.category', 'reviews.book', 'bookmarks.book']);

        $loans = $user->loans()->with('book.category')->latest()->paginate(10);
        $totalFines = $user->loans()->where('fine_paid', false)->sum('fine_amount');

        return view('admin.members.show', compact('user', 'loans', 'totalFines'));
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menonaktifkan satu-satunya akun Administrator.');
        }

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun pengguna {$user->name} berhasil {$statusText}.");
    }
}

