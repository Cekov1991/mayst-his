<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpectaclePrescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'doctor_id',
        'od_sphere',
        'od_cylinder',
        'od_axis',
        'od_add',
        'os_sphere',
        'os_cylinder',
        'os_axis',
        'os_add',
        'pd_distance',
        'pd_near',
        'type',
        'notes',
        'valid_until',
    ];

    protected function casts(): array
    {
        return [
            'od_sphere' => 'decimal:2',
            'od_cylinder' => 'decimal:2',
            'od_add' => 'decimal:2',
            'os_sphere' => 'decimal:2',
            'os_cylinder' => 'decimal:2',
            'os_add' => 'decimal:2',
            'pd_distance' => 'decimal:2',
            'pd_near' => 'decimal:2',
            'valid_until' => 'date',
        ];
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
