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
        Schema::table('mail_boxes', function (Blueprint $table) {
            //
            $table->integer('mark_as_read')->nullable()->default(0)->comments('0 = Not read, 1 = Yes Read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mail_boxes', function (Blueprint $table) {
            //
            drop_column('mail_boxes', 'mark_as_read');
        });
    }
};
