<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Admin\Suppliers as AdminSuppliers;
use App\Livewire\Admin\SupplierGigs as AdminSupplierGigs;
use App\Livewire\Admin\Venues as AdminVenues;
use App\Livewire\Dashboard;
use App\Livewire\EventEdit;
use App\Livewire\EventLandingPage;
use App\Livewire\EventPlanner;
use App\Livewire\GiftRegistryManager;
use App\Livewire\GiftRegistryPage;
use App\Livewire\HomePage;
use App\Livewire\PageBuilder;
use App\Livewire\ServiceDetail;
use App\Livewire\ServiceListing;
use App\Livewire\Supplier\ProfileEdit;
use App\Livewire\Supplier\Requests;
use App\Livewire\Supplier\SupplierServices;
use App\Livewire\VenueDetail;
use App\Livewire\VenueListing;
use App\Models\GiftPledge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// --- Public routes ---
Route::get('/', HomePage::class);
Route::get('/espacos', VenueListing::class);
Route::get('/espacos/{slug}', VenueDetail::class);
Route::get('/servicos/{category?}', ServiceListing::class);
Route::get('/servico/{slug}', ServiceDetail::class);
Route::get('/presentes/{slug}', GiftRegistryPage::class);

// Gift pledge cancel (public, token-based)
Route::get('/presentes/cancelar/{token}', function (string $token) {
    $pledge = GiftPledge::where('cancel_token', $token)
        ->whereNull('cancelled_at')
        ->with('giftItem.registry.event')
        ->first();

    if (! $pledge) {
        return redirect('/')->with('info', 'Link inválido ou marcação já cancelada.');
    }

    $eventSlug = $pledge->giftItem?->registry?->event?->slug;

    $pledge->update(['cancelled_at' => now()]);
    $pledge->giftItem->decrement('quantity_received');

    $redirect = $eventSlug ? "/presentes/{$eventSlug}" : '/';
    return redirect($redirect)->with('pledge_cancelled', true);
})->name('gift.pledge.cancel');

// --- Guest routes ---
Route::middleware('guest')->group(function () {
    Route::get('/cadastrar', Register::class);
    Route::get('/entrar', Login::class)->name('login');
});

// --- Auth routes ---
Route::middleware('auth')->group(function () {
    // Email verification
    Route::get('/email/verify', VerifyEmail::class)
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back();
    })->middleware('throttle:6,1')->name('verification.send');

    Route::get('/dashboard', Dashboard::class)->middleware('verified');
    Route::get('/planejar/{conversation?}', EventPlanner::class)->middleware('verified');
    Route::get('/meus-eventos/criar', EventEdit::class)->middleware('verified')->name('event.create');
    Route::get('/meus-eventos/{slug}/editar', EventEdit::class)->middleware('verified')->name('event.edit');
    Route::get('/meus-eventos/{slug}/presentes', GiftRegistryManager::class)->middleware('verified')->name('event.gifts');
    Route::get('/meus-eventos/{slug}/pagina', PageBuilder::class)->middleware('verified')->name('event.page.builder');
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

// --- Supplier routes ---
Route::middleware(['auth', 'verified'])->prefix('fornecedor')->group(function () {
    Route::get('/perfil', ProfileEdit::class);
    Route::get('/servicos', SupplierServices::class);
    Route::get('/solicitacoes', Requests::class);
});

// --- Prototype (IHC) ---
Route::prefix('prototype')->group(function () {
    Route::view('/', 'prototype.cover');
    Route::view('/cover', 'prototype.cover');
    Route::view('/moodboard', 'prototype.moodboard');
    Route::view('/style-guide', 'prototype.style-guide');
    Route::view('/components', 'prototype.components');
    Route::view('/prototype', 'prototype.prototype');
});

// --- Admin routes ---
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/fornecedores', AdminSuppliers::class)->name('admin.suppliers');
    Route::get('/fornecedores/{id}/gigs', AdminSupplierGigs::class)->name('admin.supplier.gigs');
    Route::get('/espacos', AdminVenues::class)->name('admin.venues');
});

// --- Public event landing pages (keep last to avoid conflicts) ---
Route::get('/{userSlug}/{eventSlug}', EventLandingPage::class)->name('event.landing');
