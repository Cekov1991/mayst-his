<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anamnesis extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'chief_complaint',
        'history_of_present_illness',
        'past_medical_history',
        'family_history',
        'medications_current',
        'allergies',
        'therapy_in_use',
        'other_notes',
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }
}
