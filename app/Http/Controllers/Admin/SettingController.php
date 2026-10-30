<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = [
            'library_name' => Setting::get('library_name', 'Aksaraya'),
            'library_tagline' => Setting::get('library_tagline', 'Perpustakaan Digital Nusantara Modern'),
            'loan_period_days' => Setting::get('loan_period_days', 7),
            'daily_fine_rate' => Setting::get('daily_fine_rate', 1000),
            'max_borrow_limit' => Setting::get('max_borrow_limit', 3),
            'library_address' => Setting::get('library_address', 'Jl. Merdeka Literasi No. 45, Jakarta'),
            'library_phone' => Setting::get('library_phone', '+62 21 555-2026'),
            'library_email' => Setting::get('library_email', 'layanan@aksaraya.id'),
            'announcement' => Setting::get('announcement', ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'library_name' => ['required', 'string', 'max:255'],
            'library_tagline' => ['required', 'string', 'max:255'],
            'loan_period_days' => ['required', 'integer', 'min:1', 'max:30'],
            'daily_fine_rate' => ['required', 'numeric', 'min:0'],
            'max_borrow_limit' => ['required', 'integer', 'min:1', 'max:10'],
            'library_address' => ['required', 'string', 'max:500'],
            'library_phone' => ['required', 'string', 'max:50'],
            'library_email' => ['required', 'email', 'max:100'],
            'announcement' => ['nullable', 'string', 'max:1000'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan perpustakaan berhasil disimpan!');
    }
}

