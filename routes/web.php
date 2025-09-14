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

    // Visit workspace routes
    Route::prefix('visits/{visit}')->group(function () {
        // Workspace page routes (individual pages)
        Route::get('/anamnesis', [App\Http\Controllers\VisitController::class, 'showAnamnesis'])->name('visits.anamnesis');
        Route::get('/examination', [App\Http\Controllers\VisitController::class, 'showExamination'])->name('visits.examination');
        Route::get('/imaging', [App\Http\Controllers\VisitController::class, 'showImaging'])->name('visits.imaging');
        Route::get('/treatments', [App\Http\Controllers\VisitController::class, 'showTreatments'])->name('visits.treatments');
        Route::get('/prescriptions', [App\Http\Controllers\VisitController::class, 'showPrescriptions'])->name('visits.prescriptions');
        Route::get('/spectacles', [App\Http\Controllers\VisitController::class, 'showSpectacles'])->name('visits.spectacles');

        // Individual form routes
        Route::get('/imaging/create', [App\Http\Controllers\VisitController::class, 'createImaging'])->name('visits.imaging.create');
        Route::get('/imaging/{imaging}/edit', [App\Http\Controllers\VisitController::class, 'editImaging'])->name('visits.imaging.edit');
        Route::get('/treatments/create', [App\Http\Controllers\VisitController::class, 'createTreatment'])->name('visits.treatments.create');
        Route::get('/treatments/{treatment}/edit', [App\Http\Controllers\VisitController::class, 'editTreatment'])->name('visits.treatments.edit');
        Route::get('/prescriptions/create', [App\Http\Controllers\VisitController::class, 'createPrescription'])->name('visits.prescriptions.create');
        Route::get('/prescriptions/{prescription}/edit', [App\Http\Controllers\VisitController::class, 'editPrescription'])->name('visits.prescriptions.edit');
        Route::get('/spectacles/create', [App\Http\Controllers\VisitController::class, 'createSpectacles'])->name('visits.spectacles.create');
        Route::get('/spectacles/{spectacle}/edit', [App\Http\Controllers\VisitController::class, 'editSpectacles'])->name('visits.spectacles.edit');
        Route::get('/refractions/create', [App\Http\Controllers\VisitController::class, 'createRefraction'])->name('visits.refractions.create');

        // Data submission routes (simplified, no form request classes)
        Route::post('/anamnesis', [App\Http\Controllers\VisitController::class, 'storeAnamnesis'])->name('visits.anamnesis.store');
        Route::post('/exam', [App\Http\Controllers\VisitController::class, 'storeExam'])->name('visits.exam.store');
        Route::post('/refractions', [App\Http\Controllers\VisitController::class, 'storeRefraction'])->name('visits.refractions.store');
        Route::post('/imaging', [App\Http\Controllers\VisitController::class, 'storeImaging'])->name('visits.imaging.store');
        Route::put('/imaging/{imaging}', [App\Http\Controllers\VisitController::class, 'updateImaging'])->name('visits.imaging.update');
        Route::post('/treatments', [App\Http\Controllers\VisitController::class, 'storeTreatment'])->name('visits.treatments.store');
        Route::put('/treatments/{treatment}', [App\Http\Controllers\VisitController::class, 'updateTreatment'])->name('visits.treatments.update');
        Route::post('/prescriptions', [App\Http\Controllers\VisitController::class, 'storePrescription'])->name('visits.prescriptions.store');
        Route::put('/prescriptions/{prescription}', [App\Http\Controllers\VisitController::class, 'updatePrescription'])->name('visits.prescriptions.update');
        Route::post('/spectacles', [App\Http\Controllers\VisitController::class, 'storeSpectacles'])->name('visits.spectacles.store');
        Route::put('/spectacles/{spectacle}', [App\Http\Controllers\VisitController::class, 'updateSpectacles'])->name('visits.spectacles.update');

        // Delete routes
        Route::delete('/refractions/{refraction}', [App\Http\Controllers\VisitController::class, 'destroyRefraction'])->name('visits.refractions.destroy');
        Route::delete('/imaging/{imaging}', [App\Http\Controllers\VisitController::class, 'destroyImaging'])->name('visits.imaging.destroy');
        Route::delete('/treatments/{treatment}', [App\Http\Controllers\VisitController::class, 'destroyTreatment'])->name('visits.treatments.destroy');
        Route::delete('/prescriptions/{prescription}', [App\Http\Controllers\VisitController::class, 'destroyPrescription'])->name('visits.prescriptions.destroy');
        Route::delete('/spectacles/{spectacle}', [App\Http\Controllers\VisitController::class, 'destroySpectacles'])->name('visits.spectacles.destroy');
    })->middleware(['auth']);
});
