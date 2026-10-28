<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'color',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    // Relationships
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    // Helpers
    public function getComplaintsCountAttribute(): int
    {
        return $this->complaints()->count();
    }

    public function getPendingCountAttribute(): int
    {
        return $this->complaints()->where('status', 'pending')->count();
    }
}
