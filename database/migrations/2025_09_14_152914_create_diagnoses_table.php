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
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade'); // denormalized for quick lists
            $table->foreignId('diagnosed_by')->constrained('users')->onDelete('cascade');

            $table->boolean('is_primary')->default(false);
            $table->enum('eye', ['OD', 'OS', 'OU', 'NA'])->default('NA');

            $table->string('code')->nullable();
            $table->enum('code_system', ['ICD-10', 'ICD-11', 'SNOMED', 'LOCAL'])->nullable();

            $table->string('term'); // free text label shown to user

            $table->enum('status', ['provisional', 'working', 'confirmed', 'ruled_out', 'resolved'])->default('provisional');

            $table->date('onset_date')->nullable();
            $table->enum('severity', ['mild', 'moderate', 'severe', 'unknown'])->default('unknown');
            $table->enum('acuity', ['acute', 'subacute', 'chronic', 'unknown'])->default('unknown');

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['visit_id', 'is_primary']);
            $table->index(['patient_id', 'status']);
            $table->index('diagnosed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnoses');
    }
};
