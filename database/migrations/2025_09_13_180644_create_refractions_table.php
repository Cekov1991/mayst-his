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
        Schema::create('refractions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ophthalmic_exam_id')->constrained()->cascadeOnDelete();
            $table->string('eye'); // OD|OS
            $table->string('method'); // autorefraction|lensmeter|subjective
            $table->decimal('sphere', 5, 2)->nullable();
            $table->decimal('cylinder', 5, 2)->nullable();
            $table->smallInteger('axis')->nullable();
            $table->decimal('add_power', 5, 2)->nullable();
            $table->decimal('prism', 5, 2)->nullable();
            $table->string('base')->nullable(); // up|down|in|out
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index as specified in requirements
            $table->index(['ophthalmic_exam_id', 'eye', 'method']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refractions');
    }
};
