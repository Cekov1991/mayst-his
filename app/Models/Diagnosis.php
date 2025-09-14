<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diagnosis extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'visit_id',
        'patient_id',
        'diagnosed_by',
        'is_primary',
        'eye',
        'code',
        'code_system',
        'term',
        'status',
        'onset_date',
        'severity',
        'acuity',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'onset_date' => 'date',
        ];
    }

    // Relationships
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function diagnosedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diagnosed_by');
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByEye($query, $eye)
    {
        return $query->where('eye', $eye);
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    // Accessors
    public function getEyeDisplayAttribute(): string
    {
        return match ($this->eye) {
            'OD' => 'Right Eye (OD)',
            'OS' => 'Left Eye (OS)',
            'OU' => 'Both Eyes (OU)',
            'NA' => 'Not Applicable',
            default => $this->eye
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status) {
            'provisional' => 'Provisional',
            'working' => 'Working',
            'confirmed' => 'Confirmed',
            'ruled_out' => 'Ruled Out',
            'resolved' => 'Resolved',
            default => ucfirst($this->status)
        };
    }

    public function getSeverityDisplayAttribute(): string
    {
        return ucfirst($this->severity);
    }

    public function getAcuityDisplayAttribute(): string
    {
        return ucfirst($this->acuity);
    }
}
