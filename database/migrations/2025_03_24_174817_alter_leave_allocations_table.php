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
        Schema::table('leave_allocations', function (Blueprint $table) {

            $table->decimal('total_leaves', 15, 1)->default(0.0)->change();
            $table->decimal('used_leaves', 15, 1)->default(0.0)->change();
            $table->decimal('remaining_leaves', 15, 1)->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_allocations', function (Blueprint $table) {
            $table->integer('total_leaves')->change();
            $table->integer('used_leaves')->change();
            $table->integer('remaining_leaves')->change();
        });
    }
};
