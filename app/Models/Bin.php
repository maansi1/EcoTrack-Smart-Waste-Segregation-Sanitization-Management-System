<?php
// app/Models/Bin.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bin extends Model
{
    protected $table = 'bins';

    protected $fillable = [
        'name', 'area', 'location_description', 'latitude', 'longitude',
        'fill_level', 'status', 'bin_type', 'alert_sent', 'last_collected_at',
    ];

    protected $casts = [
        'alert_sent' => 'boolean',
        'last_collected_at' => 'datetime',
    ];

    /**
     * Recalculate status from fill_level.
     */
    public function recalcStatus(): void
    {
        if ($this->fill_level >= 90) {
            $this->status = 'overflow';
        } elseif ($this->fill_level >= 75) {
            $this->status = 'full';
        } elseif ($this->fill_level >= 40) {
            $this->status = 'medium';
        } else {
            $this->status = 'low';
        }
        $this->save();
    }

    public function getStatusColorAttribute(): string
    {
        switch ($this->status) {
            case 'low':
                return 'success';
            case 'medium':
                return 'warning';
            case 'full':
            case 'overflow':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    public function getStatusEmojiAttribute(): string
    {
        switch ($this->status) {
            case 'low':
                return '🟢';
            case 'medium':
                return '🟡';
            case 'full':
                return '🔴';
            case 'overflow':
                return '🚨';
            default:
                return '⚪';
        }
    }

    public function scopeNeedsCollection($query)
    {
        return $query->whereIn('status', ['full', 'overflow']);
    }
}
