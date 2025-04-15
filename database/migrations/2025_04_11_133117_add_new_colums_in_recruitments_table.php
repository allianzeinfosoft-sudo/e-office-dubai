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
        Schema::table('recruitments', function (Blueprint $table) {
            //
            $table->integer('status')->nullable();
            $table->text('status_reason')->nullable();
            $table->integer('draft_status')->nullable()->default(0)->comments('0 = No, 1 = Yes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recruitments', function (Blueprint $table) {
            //
            $table->dropColumn(['status', 'status_reason', 'draft_status']);
        });
    }
};
