<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OphthalmicExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'visus_od',
        'visus_os',
        'iop_od',
        'iop_os',
        'anterior_segment_findings_od',
        'posterior_segment_findings_od',
        'anterior_segment_findings_os',
        'posterior_segment_findings_os',
    ];

    protected function casts(): array
    {
        return [
            'iop_od' => 'decimal:2',
            'iop_os' => 'decimal:2',
        ];
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function refractions(): HasMany
    {
        return $this->hasMany(Refraction::class);
    }
}
