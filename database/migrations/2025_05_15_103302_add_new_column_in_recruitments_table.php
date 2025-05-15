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
            $table->integer('approval_status')->default('0')->comment('0 = pending, 1 = approved, 2= rejected');
            $table->string('approval_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recruitments', function (Blueprint $table) {
            //
            $table->dropColumn('approval_status');
            $table->dropColumn('approval_reason');
        });
    }
};
