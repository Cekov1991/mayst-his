<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'sex',
        'dob',
        'phone',
        'email',
        'address',
        'city',
        'country',
        'unique_master_citizen_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
        ];
    }

    // Relationships
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    // Scopes
    public function scopeVisibleTo($query, User $user)
    {
        // Admins and receptionists can see all patients
        if ($user->hasRole(['admin', 'receptionist'])) {
            return $query;
        }

        // Doctors can only see patients they have visits with
        if ($user->hasRole('doctor')) {
            return $query->whereHas('visits', function ($q) use ($user) {
                $q->where('doctor_id', $user->id);
            });
        }

        // Default: no patients visible
        return $query->whereRaw('1 = 0');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
