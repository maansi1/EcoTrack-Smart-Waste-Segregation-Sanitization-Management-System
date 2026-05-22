<?php
// app/Models/Report.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'generated_by', 'title', 'type', 'period', 'from_date', 'to_date', 'data', 'file_path',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date'   => 'date',
        'data'      => 'array',
    ];

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
