<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ophthalmic_exam_id',
        'eye',
        'method',
        'sphere',
        'cylinder',
        'axis',
        'add_power',
        'prism',
        'base',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'sphere' => 'decimal:2',
            'cylinder' => 'decimal:2',
            'add_power' => 'decimal:2',
            'prism' => 'decimal:2',
        ];
    }

    public function ophthalmicExam(): BelongsTo
    {
        return $this->belongsTo(OphthalmicExam::class);
    }
}
