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
        Schema::table('attendances', function (Blueprint $table) {
            $table->boolean('is_incomplete')->default(0)->comment('0 = no, 1 = yes');
            $table->boolean('incomplete_approved')->default(0)->comment('0 = pending, 1 = approved');
            $table->unsignedBigInteger('incomplete_approved_by')->nullable();
            $table->timestamp('incomplete_approved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'is_incomplete',
                'incomplete_approved',
                'incomplete_approved_by',
                'incomplete_approved_at'
            ]);
        });
    }
};
