<?php
// database/migrations/2024_01_01_000003_create_bins_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bins', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // e.g. "Block A - Gate 3"
            $table->string('area');
            $table->string('location_description')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('fill_level')->default(0); // 0-100 percent
            $table->enum('status', ['low', 'medium', 'full', 'overflow'])->default('low');
            $table->enum('bin_type', ['general', 'dry', 'wet', 'plastic', 'ewaste', 'hazardous'])->default('general');
            $table->boolean('alert_sent')->default(false);
            $table->timestamp('last_collected_at')->nullable();
            $table->timestamps();
        });

        Schema::create('sanitization_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('area');
            $table->string('task_type'); // floor sanitization, bin cleaning, etc.
            $table->text('description')->nullable();
            $table->date('scheduled_date');
            $table->time('scheduled_time');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->integer('completion_percent')->default(0);
            $table->text('completion_notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['complaint', 'task', 'bin', 'reminder', 'points', 'system'])->default('system');
            $table->string('icon')->nullable();
            $table->boolean('is_read')->default(false);
            $table->string('link')->nullable(); // URL to navigate to
            $table->timestamps();
        });

        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('complaint_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('rating')->default(0); // 1-5 stars
            $table->text('comment')->nullable();
            $table->string('area')->nullable(); // area cleanliness rating
            $table->enum('type', ['complaint', 'area_cleanliness', 'general'])->default('general');
            $table->timestamps();
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->enum('type', ['waste', 'cleanliness', 'staff', 'sanitization', 'custom'])->default('waste');
            $table->enum('period', ['daily', 'weekly', 'monthly', 'yearly', 'custom'])->default('monthly');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->longText('data')->nullable(); // JSON report data
            $table->string('file_path')->nullable(); // PDF path
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('sanitization_tasks');
        Schema::dropIfExists('bins');
    }
};
