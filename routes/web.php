<?php

use App\Helpers\Locale\LocaleHelper;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Locale switching route
Route::get('/locale/{locale}', function (string $locale) {
    if (LocaleHelper::setLocale($locale)) {
        return redirect()->back()->with('success', __('his.messages.locale_changed'));
    }

    return redirect()->back()->with('error', __('his.messages.invalid_locale'));
})->name('locale.switch');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Doctor Queue
    Route::get('/doctor-queue', function () {
        return view('doctor-queue');
    })->name('doctor.queue')->middleware('can:accessDoctorQueue');

    // Patient management routes
    Route::resource('patients', App\Http\Controllers\PatientController::class);
    Route::get('/patients-search', [App\Http\Controllers\PatientController::class, 'search'])->name('patients.search');

    // Visit management routes
    Route::resource('visits', App\Http\Controllers\VisitController::class);
    Route::patch('/visits/{visit}/status', [App\Http\Controllers\VisitController::class, 'updateStatus'])->name('visits.updateStatus');
});
