<?php

use App\Helpers\Locale\LocaleHelper;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Locale switching route
Route::get('/locale/{locale}', function (string $locale) {
    if (LocaleHelper::setLocale($locale)) {
        return redirect()->back()->with('success', __('common.messages.locale_changed'));
    }

    return redirect()->back()->with('error', __('common.messages.invalid_locale'));
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
    Route::get('/visits/{visit}/copy-from-previous-visit/{previousVisit}', [App\Http\Controllers\VisitController::class, 'showCopySelection'])->name('visits.copy_selection');
    Route::post('/visits/{visit}/copy-from-previous-visit/{previousVisit}', [App\Http\Controllers\VisitController::class, 'processCopy'])->name('visits.process_copy');

    // Slot management routes
    Route::resource('slots', App\Http\Controllers\SlotController::class);
    Route::patch('/slots/{slot}/status', [App\Http\Controllers\SlotController::class, 'updateStatus'])->name('slots.updateStatus');

    // Visit workspace routes - Medical workspace requires doctor access
    Route::prefix('visits/{visit}')->middleware(['auth'])->group(function () {
        // PDF Report Generation
        Route::get('/pdf', [App\Http\Controllers\VisitPdfController::class, 'generatePdf'])->name('visits.pdf');

        // Workspace page routes (individual pages)
        Route::get('/anamnesis', [App\Http\Controllers\AnamnesisController::class, 'show'])->name('visits.anamnesis');
        Route::get('/examination', [App\Http\Controllers\ExaminationController::class, 'show'])->name('visits.examination');
        Route::get('/imaging', [App\Http\Controllers\ImagingController::class, 'show'])->name('visits.imaging');
        Route::get('/treatments', [App\Http\Controllers\TreatmentController::class, 'show'])->name('visits.treatments');
        Route::get('/prescriptions', [App\Http\Controllers\PrescriptionController::class, 'show'])->name('visits.prescriptions');
        Route::get('/spectacles', [App\Http\Controllers\SpectacleController::class, 'show'])->name('visits.spectacles');
        Route::get('/diagnoses', [App\Http\Controllers\DiagnosisController::class, 'index'])->name('visits.diagnoses');

        // Individual form routes
        Route::get('/imaging/create', [App\Http\Controllers\ImagingController::class, 'create'])->name('visits.imaging.create');
        Route::get('/imaging/{imaging}/edit', [App\Http\Controllers\ImagingController::class, 'edit'])->name('visits.imaging.edit');
        Route::get('/treatments/create', [App\Http\Controllers\TreatmentController::class, 'create'])->name('visits.treatments.create');
        Route::get('/treatments/{treatment}/edit', [App\Http\Controllers\TreatmentController::class, 'edit'])->name('visits.treatments.edit');
        Route::get('/prescriptions/create', [App\Http\Controllers\PrescriptionController::class, 'create'])->name('visits.prescriptions.create');
        Route::get('/prescriptions/{prescription}/edit', [App\Http\Controllers\PrescriptionController::class, 'edit'])->name('visits.prescriptions.edit');
        Route::get('/spectacles/create', [App\Http\Controllers\SpectacleController::class, 'create'])->name('visits.spectacles.create');
        Route::get('/spectacles/{spectacle}/edit', [App\Http\Controllers\SpectacleController::class, 'edit'])->name('visits.spectacles.edit');
        Route::get('/refractions/create', [App\Http\Controllers\ExaminationController::class, 'createRefraction'])->name('visits.refractions.create');
        Route::get('/diagnoses/create', [App\Http\Controllers\DiagnosisController::class, 'create'])->name('visits.diagnosis.create');
        Route::get('/diagnoses/{diagnosis}/edit', [App\Http\Controllers\DiagnosisController::class, 'edit'])->name('visits.diagnosis.edit');

        // Data submission routes (simplified, no form request classes)
        Route::post('/anamnesis', [App\Http\Controllers\AnamnesisController::class, 'store'])->name('visits.anamnesis.store');
        Route::post('/exam', [App\Http\Controllers\ExaminationController::class, 'store'])->name('visits.exam.store');
        Route::post('/refractions', [App\Http\Controllers\ExaminationController::class, 'storeRefraction'])->name('visits.refractions.store');
        Route::post('/imaging', [App\Http\Controllers\ImagingController::class, 'store'])->name('visits.imaging.store');
        Route::put('/imaging/{imaging}', [App\Http\Controllers\ImagingController::class, 'update'])->name('visits.imaging.update');
        Route::post('/treatments', [App\Http\Controllers\TreatmentController::class, 'store'])->name('visits.treatments.store');
        Route::put('/treatments/{treatment}', [App\Http\Controllers\TreatmentController::class, 'update'])->name('visits.treatments.update');
        Route::post('/prescriptions', [App\Http\Controllers\PrescriptionController::class, 'store'])->name('visits.prescriptions.store');
        Route::put('/prescriptions/{prescription}', [App\Http\Controllers\PrescriptionController::class, 'update'])->name('visits.prescriptions.update');
        Route::post('/spectacles', [App\Http\Controllers\SpectacleController::class, 'store'])->name('visits.spectacles.store');
        Route::put('/spectacles/{spectacle}', [App\Http\Controllers\SpectacleController::class, 'update'])->name('visits.spectacles.update');
        Route::post('/diagnoses', [App\Http\Controllers\DiagnosisController::class, 'store'])->name('visits.diagnosis.store');
        Route::put('/diagnoses/{diagnosis}', [App\Http\Controllers\DiagnosisController::class, 'update'])->name('visits.diagnosis.update');

        // Delete routes
        Route::delete('/refractions/{refraction}', [App\Http\Controllers\ExaminationController::class, 'destroyRefraction'])->name('visits.refractions.destroy');
        Route::delete('/imaging/{imaging}', [App\Http\Controllers\ImagingController::class, 'destroy'])->name('visits.imaging.destroy');
        Route::delete('/treatments/{treatment}', [App\Http\Controllers\TreatmentController::class, 'destroy'])->name('visits.treatments.destroy');
        Route::delete('/prescriptions/{prescription}', [App\Http\Controllers\PrescriptionController::class, 'destroy'])->name('visits.prescriptions.destroy');
        Route::delete('/spectacles/{spectacle}', [App\Http\Controllers\SpectacleController::class, 'destroy'])->name('visits.spectacles.destroy');
        Route::delete('/diagnoses/{diagnosis}', [App\Http\Controllers\DiagnosisController::class, 'destroy'])->name('visits.diagnosis.destroy');
    });
});
