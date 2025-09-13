<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagingStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'modality',
        'eye',
        'ordered_by',
        'performed_by',
        'performed_at',
        'status',
        'findings',
        'attachments_json',
    ];

    protected function casts(): array
    {
        return [
            'performed_at' => 'datetime',
            'attachments_json' => 'array',
        ];
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function orderedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ordered_by');
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
