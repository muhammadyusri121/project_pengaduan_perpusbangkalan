<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_code',
        'category_id',
        'user_id',
        'reporter_name',
        'reporter_phone',
        'reporter_email',
        'subject',
        'description',
        'location',
        'status',
        'priority',
        'is_anonymous',
        'rejection_reason',
        'resolved_at',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'resolved_at'  => 'datetime',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(ComplaintAttachment::class);
    }

    public function responses()
    {
        return $this->hasMany(ComplaintResponse::class)->orderBy('created_at', 'asc');
    }

    public function publicResponses()
    {
        return $this->hasMany(ComplaintResponse::class)
            ->where('is_public', true)
            ->orderBy('created_at', 'asc');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status));
        $query->when($filters['category'] ?? null, fn($q, $cat) => $q->where('category_id', $cat));
        $query->when($filters['priority'] ?? null, fn($q, $prio) => $q->where('priority', $prio));
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('tracking_code', 'ilike', "%{$search}%")
                    ->orWhere('subject', 'ilike', "%{$search}%")
                    ->orWhere('reporter_name', 'ilike', "%{$search}%")
                    ->orWhere('reporter_phone', 'ilike', "%{$search}%");
            });
        });

        return $query;
    }

    // Helpers
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'     => 'Masuk',
            'in_progress' => 'Diproses',
            'resolved'    => 'Selesai',
            'rejected'    => 'Ditolak',
            default       => 'Tidak Diketahui',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending'     => 'badge-warning',
            'in_progress' => 'badge-info',
            'resolved'    => 'badge-success',
            'rejected'    => 'badge-danger',
            default       => 'badge-secondary',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'low'    => 'Rendah',
            'medium' => 'Sedang',
            'high'   => 'Tinggi',
            default  => 'Sedang',
        };
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match($this->priority) {
            'low'    => 'badge-secondary',
            'medium' => 'badge-primary',
            'high'   => 'badge-danger',
            default  => 'badge-primary',
        };
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->is_anonymous ? 'Anonim' : $this->reporter_name;
    }
}
