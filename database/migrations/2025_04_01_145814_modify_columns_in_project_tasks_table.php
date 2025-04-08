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
        Schema::table('project_tasks', function (Blueprint $table) {
            //
            $table->renameColumn('pr_task_id', 'reporting_to');
            $table->renameColumn('pr_sub_task_id', 'members');
        });

        Schema::table('project_tasks', function (Blueprint $table) {
            $table->text('members')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_tasks', function (Blueprint $table) {
            $table->string('members')->nullable()->change();
        });

        Schema::table('project_tasks', function (Blueprint $table) {
            // Revert column names
            $table->renameColumn('reporting_to', 'pr_task_id');
            $table->renameColumn('members', 'pr_sub_task_id');
        });
    }
};
