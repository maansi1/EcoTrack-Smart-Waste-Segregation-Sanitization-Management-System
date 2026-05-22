<?php
// database/migrations/2024_01_01_000001_create_waste_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // e.g. "Dry Waste"
            $table->string('slug')->unique();    // e.g. "dry-waste"
            $table->string('color')->default('#2da563'); // UI color
            $table->string('icon')->nullable();  // emoji or icon class
            $table->string('bin_color')->nullable(); // "Green", "Blue", etc.
            $table->text('description')->nullable();
            $table->text('disposal_instructions')->nullable();
            $table->text('recycling_tips')->nullable();
            $table->boolean('is_hazardous')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_categories');
    }
};
