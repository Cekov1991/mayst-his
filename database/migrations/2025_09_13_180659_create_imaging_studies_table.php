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
        Schema::create('imaging_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->string('modality'); // OCT|VF|US|FA|Biometry|Photo|Other
            $table->string('eye'); // OD|OS|OU|NA
            $table->foreignId('ordered_by')->nullable()->constrained('users');
            $table->foreignId('performed_by')->nullable()->constrained('users');
            $table->dateTime('performed_at')->nullable();
            $table->string('status'); // ordered|done|reported
            $table->text('findings')->nullable();
            $table->json('attachments_json')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaging_studies');
    }
};
