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
        Schema::table('productivity_targets', function (Blueprint $table) {
            //
            $table->integer('assignedBy')->after('project_task_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productivity_targets', function (Blueprint $table) {
            //
            $table->dropColumn('assignedBy');
        });
    }
};
