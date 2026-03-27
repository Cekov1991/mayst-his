<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->date('valid_from');
            $table->date('valid_to');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('slot_interval'); // in minutes
            $table->json('days_of_week'); // [1,2,3,4,5] for Mon-Fri
            $table->json('week_pattern')->nullable(); // [1,3] for 1st and 3rd week of month
            $table->json('specific_dates')->nullable(); // ["2025-11-01", "2025-11-15"]
            $table->json('excluded_dates')->nullable(); // ["2025-11-20"]
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['doctor_id', 'valid_from', 'valid_to']);
            $table->index(['is_active', 'valid_from', 'valid_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
