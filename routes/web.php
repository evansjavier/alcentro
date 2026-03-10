<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PremiseController;
use App\Http\Controllers\ContractController;
use App\Livewire\Clients\Index as ClientsIndex;
use App\Livewire\Contracts\Index as ContractsIndex;
use App\Livewire\Premises\Index as PremisesIndex;
use App\Livewire\Demo1\Index as Demo1Index;
use App\Livewire\Demo2\Index as Demo2Index;
use App\Livewire\Demo3\Index as Demo3Index;
use App\Livewire\Demo4\Index as Demo4Index;
use App\Livewire\Demo5\Index as Demo5Index;
use App\Livewire\Demo6\Index as Demo6Index;
use App\Livewire\Demo7\Index as Demo7Index;
use App\Livewire\Demo8\Index as Demo8Index;
use App\Livewire\Demo9\Index as Demo9Index;
use App\Livewire\Demo10\Index as Demo10Index;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/clients', ClientsIndex::class)
    ->middleware(['auth', 'verified'])
    ->name('clients.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/clients/create', \App\Livewire\Clients\Create::class)
        ->name('clients.create');
        
    Route::get('/clients/{client}/edit', \App\Livewire\Clients\Edit::class)
        ->name('clients.edit');

    Route::get('/premises', PremisesIndex::class)
        ->name('premises.index');
        
    Route::get('/premises/create', [PremiseController::class, 'create'])
        ->name('premises.create');

    Route::get('/premises/{premise}/edit', [PremiseController::class, 'edit'])
        ->name('premises.edit');

    Route::put('/premises/{premise}', [PremiseController::class, 'update'])
        ->name('premises.update');

    Route::post('/premises', [PremiseController::class, 'store'])
        ->name('premises.store');

    Route::get('/contracts', ContractsIndex::class)
        ->name('contracts.index');

    Route::get('/contracts/create', [ContractController::class, 'create'])
        ->name('contracts.create');

    Route::post('/contracts', [ContractController::class, 'store'])
        ->name('contracts.store');

    Route::get('/contracts/{contract}', [ContractController::class, 'show'])
        ->name('contracts.show');
    Route::get('/contracts/{contract}/terminate', [ContractController::class, 'terminate'])
        ->name('contracts.terminate');

    Route::post('/contracts/{contract}/terminate', [ContractController::class, 'processTermination'])
        ->name('contracts.processTermination');});

Route::get('/demo1', Demo1Index::class)->name('demo1.index');
Route::get('/demo2', Demo2Index::class)->name('demo2.index');
Route::get('/demo3', Demo3Index::class)->name('demo3.index');
Route::get('/demo4', Demo4Index::class)->name('demo4.index');
Route::get('/demo5', Demo5Index::class)->name('demo5.index');
Route::get('/demo6', Demo6Index::class)->name('demo6.index');
Route::get('/demo7', Demo7Index::class)->name('demo7.index');
Route::get('/demo8', Demo8Index::class)->name('demo8.index');
Route::get('/demo9', Demo9Index::class)->name('demo9.index');
Route::get('/demo10', Demo10Index::class)->name('demo10.index');

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('/profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/invoices', \App\Livewire\Invoices\Index::class)->name('invoices.index');
    Route::get('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'show'])->name('invoices.show');

    // Payments
    Route::get('/invoices/{invoice}/payments/create', \App\Livewire\Payments\Create::class)->name('invoices.payments.create');
    Route::get('/payments/{payment}/edit', \App\Livewire\Payments\Edit::class)->name('payments.edit');
    Route::get('/payments', \App\Livewire\Payments\Index::class)->name('payments.index');
});
