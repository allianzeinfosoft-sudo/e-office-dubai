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
        Schema::table('allocation_line_items', function (Blueprint $table) {
            $table->dateTime('return_date_time')->nullable()->after('status');
            $table->text('comment')->nullable()->after('return_date_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('allocation_line_items', function (Blueprint $table) {
           $table->dropColumn(['return_date_time', 'comment']);
        });
    }
};
