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

    // Visit workspace routes (placeholder functionality)
    Route::prefix('visits/{visit}')->group(function () {
        // Anamnesis routes
        Route::post('/anamnesis', function () {
            return redirect()->back()->with('anamnesis_success', 'Anamnesis saved successfully.');
        })->name('visits.anamnesis.store');

        // Exam routes
        Route::post('/exam', function () {
            return redirect()->back()->with('exam_success', 'Examination saved successfully.');
        })->name('visits.exam.store');

        // Refraction routes
        Route::post('/refractions', function () {
            return redirect()->back()->with('refraction_success', 'Refraction saved successfully.');
        })->name('visits.refractions.store');
        Route::delete('/refractions/{refraction}', function () {
            return redirect()->back()->with('refraction_success', 'Refraction deleted successfully.');
        })->name('visits.refractions.destroy');

        // Imaging routes
        Route::post('/imaging', function () {
            return redirect()->back()->with('imaging_success', 'Imaging study saved successfully.');
        })->name('visits.imaging.store');
        Route::delete('/imaging/{imaging}', function () {
            return redirect()->back()->with('imaging_success', 'Imaging study deleted successfully.');
        })->name('visits.imaging.destroy');

        // Treatment routes
        Route::post('/treatments', function () {
            return redirect()->back()->with('treatment_success', 'Treatment plan saved successfully.');
        })->name('visits.treatments.store');
        Route::delete('/treatments/{treatment}', function () {
            return redirect()->back()->with('treatment_success', 'Treatment plan deleted successfully.');
        })->name('visits.treatments.destroy');

        // Prescription routes
        Route::post('/prescriptions', function () {
            return redirect()->back()->with('prescription_success', 'Prescription saved successfully.');
        })->name('visits.prescriptions.store');
        Route::delete('/prescriptions/{prescription}', function () {
            return redirect()->back()->with('prescription_success', 'Prescription deleted successfully.');
        })->name('visits.prescriptions.destroy');

        // Spectacle routes
        Route::post('/spectacles', function () {
            return redirect()->back()->with('spectacles_success', 'Spectacle prescription saved successfully.');
        })->name('visits.spectacles.store');
        Route::delete('/spectacles/{spectacle}', function () {
            return redirect()->back()->with('spectacles_success', 'Spectacle prescription deleted successfully.');
        })->name('visits.spectacles.destroy');
    });
});
