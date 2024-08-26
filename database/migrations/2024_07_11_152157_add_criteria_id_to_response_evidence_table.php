<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCriteriaIdToResponseEvidenceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('response_evidence', function (Blueprint $table) {
            if (!Schema::hasColumn('response_evidence', 'criteria_id')) {
                $table->unsignedBigInteger('criteria_id')->nullable(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('response_evidence', function (Blueprint $table) {
            if (Schema::hasColumn('response_evidence', 'criteria_id')) {
                $table->dropColumn('criteria_id');
            }
        });
    }
}
