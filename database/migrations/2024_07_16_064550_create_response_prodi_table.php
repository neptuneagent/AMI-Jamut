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
        Schema::create('response_prodis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_finding_id');
            $table->foreign('response_finding_id')->references('id')->on('response_findings')->onDelete('cascade');
            $table->text('comment')->nullable();
            $table->text('corrective_action_plan')->nullable();
            $table->date('corrective_action_schedule')->nullable();
            $table->text('preventive_action_plan')->nullable();
            $table->date('preventive_action_schedule')->nullable();
            $table->string('corrective_action_responsible')->nullable();
            $table->string('preventive_action_responsible')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('response_prodi');
    }
};
