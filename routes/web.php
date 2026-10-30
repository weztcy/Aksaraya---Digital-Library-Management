<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\LoanManagementController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Member\LoanController as MemberLoanController;
use App\Http\Controllers\Member\MemberDashboardController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [HomeController::class, 'about'])->name('about');
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/katalog/{slug}/baca', [CatalogController::class, 'readEbook'])->name('catalog.read');

// Guest authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.post');
});

// Authenticated shared routes
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Member authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/katalog/{id}/favorit', [CatalogController::class, 'toggleBookmark'])->name('catalog.bookmark');
    Route::post('/katalog/{id}/ulasan', [CatalogController::class, 'storeReview'])->name('catalog.review');
    Route::post('/katalog/{id}/progress', [CatalogController::class, 'updateReadingProgress'])->name('catalog.progress');

    Route::prefix('anggota')->name('member.')->group(function () {
        Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pinjaman', [MemberDashboardController::class, 'loans'])->name('loans');
        Route::post('/pinjam', [MemberLoanController::class, 'store'])->name('loan.store');
        Route::post('/pinjaman/{id}/batal', [MemberDashboardController::class, 'cancelLoan'])->name('loan.cancel');
        Route::post('/pinjaman/{id}/bayar-simulasi', [MemberDashboardController::class, 'payFineSimulated'])->name('loan.paySimulated');
        Route::get('/kartu-anggota', [MemberDashboardController::class, 'card'])->name('card');
        Route::get('/favorit', [MemberDashboardController::class, 'bookmarks'])->name('bookmarks');
        Route::get('/rak-buku', [MemberDashboardController::class, 'shelves'])->name('shelves');
        Route::post('/rak-buku/{bookId}', [MemberDashboardController::class, 'updateShelf'])->name('shelves.update');
        Route::post('/tantangan-membaca', [MemberDashboardController::class, 'saveChallenge'])->name('challenge.save');
        Route::get('/profil', [MemberDashboardController::class, 'profile'])->name('profile');
        Route::put('/profil', [MemberDashboardController::class, 'updateProfile'])->name('profile.update');
    });
});

// Admin & Staff routes
Route::middleware(['auth', 'role:admin,petugas'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Books management
    Route::get('/books/export/csv', [AdminBookController::class, 'exportCsv'])->name('books.export');
    Route::post('/books/import/csv', [AdminBookController::class, 'importCsv'])->name('books.import');
    Route::resource('books', AdminBookController::class);

    // Categories management
    Route::resource('categories', AdminCategoryController::class)->except(['show', 'create', 'edit']);

    // Loans circulation management
    Route::get('/peminjaman', [LoanManagementController::class, 'index'])->name('loans.index');
    Route::get('/peminjaman/tambah', [LoanManagementController::class, 'create'])->name('loans.create');
    Route::post('/peminjaman', [LoanManagementController::class, 'store'])->name('loans.store');
    Route::post('/peminjaman/{id}/setujui', [LoanManagementController::class, 'approve'])->name('loans.approve');
    Route::post('/peminjaman/{id}/serahkan', [LoanManagementController::class, 'borrow'])->name('loans.borrow');
    Route::post('/peminjaman/{id}/kembalikan', [LoanManagementController::class, 'returnBook'])->name('loans.return');
    Route::post('/peminjaman/{id}/bayar-denda', [LoanManagementController::class, 'payFine'])->name('loans.payFine');
    Route::post('/peminjaman/{id}/tolak', [LoanManagementController::class, 'reject'])->name('loans.reject');
    Route::post('/peminjaman/{id}/ingatkan', [LoanManagementController::class, 'sendReminder'])->name('loans.reminder');
    Route::get('/peminjaman/scan-barcode', [LoanManagementController::class, 'lookupBarcode'])->name('loans.scan');

    // Members management
    Route::get('/anggota', [AdminMemberController::class, 'index'])->name('members.index');
    Route::get('/anggota/{user}', [AdminMemberController::class, 'show'])->name('members.show');
    Route::post('/anggota/{user}/status', [AdminMemberController::class, 'toggleStatus'])->name('members.toggleStatus');

    // Reports
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');

    // Settings (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/pengaturan', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/pengaturan', [SettingController::class, 'update'])->name('settings.update');
    });
});
