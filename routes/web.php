<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

// Email Verification
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {

    $user = User::findOrFail($id);

    if (! hash_equals(
        sha1($user->getEmailForVerification()),
        $hash
    )) {
        abort(403);
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect('/login')
        ->with('success', 'Email verified successfully. Please login.');

})->middleware('signed')->name('verification.verify');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');


    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');

});

use Illuminate\Http\Request;

Route::post('/notification/read/{id}', function ($id, Request $request) {
    $notification = $request->user()->notifications()->find($id);

    if ($notification) {
        $notification->markAsRead();
    }

    return back();
})->name('notification.read');

Route::post('/notifications/read-all', function (Request $request) {
    $request->user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.readAll')->middleware('auth');

// Auth Routes
require __DIR__.'/auth.php';


// Controllers
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\ReturnController;


/* Livewire Components */

// Categories
use App\Livewire\Categories\Index as CategoryIndex;
use App\Livewire\Categories\Create as CategoryCreate;
use App\Livewire\Categories\Edit as CategoryEdit;

// Items
use App\Livewire\Items\Index as ItemIndex;
use App\Livewire\Items\Create as ItemCreate;
use App\Livewire\Items\Edit as ItemEdit;

// Suppliers
use App\Livewire\Suppliers\Index as SupplierIndex;
use App\Livewire\Suppliers\Create as SupplierCreate;
use App\Livewire\Suppliers\Edit as SupplierEdit;

// Barcodes
use App\Livewire\Barcodes\Index as BarcodeIndex;

// Scan
use App\Livewire\Scan\Index as ScanIndex;

// Returns
use App\Livewire\Return\Index as ReturnIndex;

// Reports
use App\Livewire\Reports\Index as ReportsIndex;

// Dashboard
use App\Livewire\Dashboard\Index as DashboardIndex;

// Settings
use App\Livewire\Settings\Index as SettingsIndex;



// Redirect root
Route::view('/', 'welcome');


Route::get('/register-success', function () {
    return view('auth.register-success');
})->name('register.success');

// AUTH ROUTES (ALL USERS)

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');

    // Categories
    Route::get('/categories', CategoryIndex::class)->name('categories');
    Route::get('/categories/create', CategoryCreate::class);
    Route::get('/categories/{id}/edit', CategoryEdit::class);
    
    // Items
    Route::get('/items', ItemIndex::class)->name('items');
    Route::get('/items/create', ItemCreate::class);
    Route::get('/items/{id}/edit', ItemEdit::class);

    // Suppliers
    Route::get('/suppliers', SupplierIndex::class)->name('suppliers');
    Route::get('/suppliers/create', SupplierCreate::class);
    Route::get('/suppliers/{id}/edit', SupplierEdit::class);

    // Barcodes
    Route::get('/barcodes', BarcodeIndex::class)->name('barcodes');
    Route::get('/barcodes/download/{id}', [BarcodeController::class, 'download']);
    Route::get('/barcodes/export', [BarcodeController::class, 'export']);
    Route::get('/barcodes/export/pdf', [BarcodeController::class, 'exportPdf']);
    Route::get('/barcodes/print', [BarcodeController::class, 'print']);

    // Scan
    Route::get('/scan', ScanIndex::class)->name('scan');

    // Receipt
    Route::get('/receipt/{sale}', [ReceiptController::class, 'print'])->name('receipt.print');

    // Returns
    Route::get('/returns', ReturnIndex::class)->name('returns');
    Route::get('/return/{id}/receipt', [ReturnController::class, 'receipt'])->name('return.receipt');

    // Sales History
    Route::get('/sales-history', \App\Livewire\Sales\History::class)->name('sales.history');
});


// ADMIN ONLY ROUTES
Route::middleware(['auth', 'admin'])->group(function () {

    // Reports
    Route::get('/reports', ReportsIndex::class)->name('reports');

});

Route::middleware(['auth'])->group(function () {

    // Settings
    Route::get('/settings', SettingsIndex::class)->name('settings');

});


use Illuminate\Support\Facades\Auth;

Route::get('/email-change-logout', function () {

    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');

});

