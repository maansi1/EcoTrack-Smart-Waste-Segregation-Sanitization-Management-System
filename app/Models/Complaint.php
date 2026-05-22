<?php
// app/Models/Complaint.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_number', 'user_id', 'waste_category_id', 'assigned_to',
        'title', 'description', 'building', 'area', 'specific_location',
        'latitude', 'longitude', 'image_path', 'status', 'priority',
        'resolution_notes', 'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function wasteCategory()
    {
        return $this->belongsTo(WasteCategory::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePending($query)         { return $query->where('status', 'pending'); }
    public function scopeInProgress($query)      { return $query->where('status', 'in_progress'); }
    public function scopeResolved($query)        { return $query->where('status', 'resolved'); }
    public function scopeByArea($query, $area)   { return $query->where('area', $area); }
    public function scopeUrgent($query)          { return $query->where('priority', 'urgent'); }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function getStatusBadgeAttribute(): string
    {
        switch ($this->status) {
            case 'pending':
                return '<span class="badge badge-pending">Pending</span>';
            case 'in_progress':
                return '<span class="badge badge-progress">In Progress</span>';
            case 'resolved':
                return '<span class="badge badge-resolved">Resolved</span>';
            case 'closed':
                return '<span class="badge badge-closed">Closed</span>';
            default:
                return '<span class="badge">Unknown</span>';
        }
    }
 
    public function getPriorityBadgeAttribute(): string
    {
        switch ($this->priority) {
            case 'urgent':
                return '<span class="badge badge-urgent">Urgent</span>';
            case 'high':
                return '<span class="badge badge-high">High</span>';
            default:
                return '<span class="badge badge-normal">Normal</span>';
        }
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($complaint) {
            $last = static::max('id') ?? 0;
            $complaint->complaint_number = 'C-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}
