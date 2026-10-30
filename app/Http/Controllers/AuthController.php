<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->with('error', 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin perpustakaan.');
            }

            $request->session()->regenerate();

            return $this->redirectBasedOnRole($user)
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Email atau password yang Anda masukkan salah.']);
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        // Generate unique member number: PKN-YYYYMM-XXXX
        $yearMonth = date('Ym');
        $lastMember = User::where('member_number', 'like', "PKN-{$yearMonth}-%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastMember && preg_match('/-(\d+)$/', $lastMember->member_number, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }
        $memberNumber = sprintf('PKN-%s-%04d', $yearMonth, $sequence);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'anggota',
            'member_number' => $memberNumber,
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('member.dashboard')
            ->with('success', 'Pendaftaran berhasil! Nomor Kartu Anggota Anda: ' . $memberNumber);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }

    private function redirectBasedOnRole(User $user): RedirectResponse
    {
        if ($user->isStaff()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('member.dashboard');
    }
}

