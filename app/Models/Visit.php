<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'slot_id',
        'type',
        'status',
        'scheduled_at',
        'arrived_at',
        'started_at',
        'completed_at',
        'reason_for_visit',
        'room',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'arrived_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class);
    }

    public function anamnesis(): HasOne
    {
        return $this->hasOne(Anamnesis::class);
    }

    public function ophthalmicExam(): HasOne
    {
        return $this->hasOne(OphthalmicExam::class);
    }

    public function imagingStudies(): HasMany
    {
        return $this->hasMany(ImagingStudy::class);
    }

    public function treatmentPlans(): HasMany
    {
        return $this->hasMany(TreatmentPlan::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function spectaclePrescriptions(): HasMany
    {
        return $this->hasMany(SpectaclePrescription::class);
    }

    public function diagnoses(): HasMany
    {
        return $this->hasMany(Diagnosis::class);
    }

    // Scopes
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeVisibleTo($query, User $user)
    {
        // Admins and receptionists can see all visits
        if ($user->hasRole(['admin', 'receptionist'])) {
            return $query;
        }

        // Doctors can only see their own visits
        if ($user->hasRole('doctor')) {
            return $query->where('doctor_id', $user->id);
        }

        // Default: no visits visible
        return $query->whereRaw('1 = 0');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }

    public function scopeQueue($query, $doctorId = null)
    {
        $query = $query->whereIn('status', ['arrived', 'in_progress', 'scheduled'])
            ->orderBy('arrived_at')
            ->orderBy('scheduled_at');

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        return $query;
    }
}
