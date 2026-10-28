<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'user_id',
        'message',
        'status_change',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // Relationships
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helpers
    public function getStatusChangeLabelAttribute(): ?string
    {
        if (!$this->status_change) return null;

        return match($this->status_change) {
            'pending'     => 'Masuk',
            'in_progress' => 'Diproses',
            'resolved'    => 'Selesai',
            'rejected'    => 'Ditolak',
            default       => null,
        };
    }
}
