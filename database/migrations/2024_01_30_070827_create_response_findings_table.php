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
        Schema::create('response_findings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_id');
            $table->foreign('response_id')->references('id')->on('responses')->onDelete('cascade');
            $table->text('description');
            $table->unsignedBigInteger('criteria_id');
            $table->foreign('criteria_id')->references('id')->on('criterias')->onDelete('cascade');
            $table->text('root_cause');
            $table->text('recommendation');
            $table->string('category')->nullable(); // Added category field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('response_findings');
    }
};
