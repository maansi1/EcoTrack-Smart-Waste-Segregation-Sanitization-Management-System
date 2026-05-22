<?php
// app/Models/WasteCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'color', 'icon', 'bin_color',
        'description', 'disposal_instructions', 'recycling_tips', 'is_hazardous',
    ];

    protected $casts = ['is_hazardous' => 'boolean'];

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}
