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
            $table->string('skillRequired', 255)->nullable()->change();
            $table->string('keyword', 255)->nullable()->change();

            $table->integer('noOfPersons')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recruitments', function (Blueprint $table) {
            //
            $table->integer('skillRequired')->nullable()->change();
            $table->integer('keyword')->nullable()->change();

            $table->dropColumn(['noOfPersons']);

        });
    }
};
