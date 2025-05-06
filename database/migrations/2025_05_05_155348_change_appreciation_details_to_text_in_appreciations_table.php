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
        Schema::table('appreciations', function (Blueprint $table) {
            $table->text('appreciation_details')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appreciations', function (Blueprint $table) {
            $table->string('appreciation_details', 255)->change();
        });
    }
};
