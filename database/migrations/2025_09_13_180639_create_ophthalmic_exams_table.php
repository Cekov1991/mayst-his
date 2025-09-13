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
        Schema::create('ophthalmic_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('visus_od')->nullable();
            $table->string('visus_os')->nullable();
            $table->decimal('iop_od', 5, 2)->nullable();
            $table->decimal('iop_os', 5, 2)->nullable();
            $table->text('anterior_segment_findings')->nullable();
            $table->text('posterior_segment_findings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ophthalmic_exams');
    }
};
