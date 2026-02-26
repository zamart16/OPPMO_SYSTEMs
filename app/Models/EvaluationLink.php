<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'token',
        'reviewer_role',
        'reviewer_email',
        'expires_at',
        'used_at',
        'is_completed',
    ];

    protected $casts = [
        'expires_at'   => 'datetime',
        'used_at'      => 'datetime',
        'is_completed' => 'boolean',
    ];

    /**
     * Relationship: Link belongs to Evaluation
     */
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    /**
     * Scope: Only active links
     */
    public function scopeActive($query)
    {
        return $query->where('is_completed', false)
                     ->where(function ($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }
}
