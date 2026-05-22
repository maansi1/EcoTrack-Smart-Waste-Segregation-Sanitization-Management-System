<?php
// app/Models/SanitizationTask.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanitizationTask extends Model
{
    protected $fillable = [
        'assigned_to', 'created_by', 'area', 'task_type', 'description',
        'scheduled_date', 'scheduled_time', 'status', 'completion_percent',
        'completion_notes', 'completed_at',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_at'   => 'datetime',
    ];

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePending($query)   { return $query->where('status', 'pending'); }
    public function scopeCompleted($query) { return $query->where('status', 'completed'); }
    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_date', today());
    }
}
