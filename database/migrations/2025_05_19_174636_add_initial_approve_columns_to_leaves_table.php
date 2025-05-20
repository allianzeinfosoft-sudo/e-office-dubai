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
        Schema::table('leaves', function (Blueprint $table) {
            $table->boolean('initial_approve_status')->default(false)->after('status');
            $table->unsignedBigInteger('initial_approver_id')->nullable()->after('initial_approve_status');
            $table->date('initial_approved_date')->nullable()->after('initial_approver_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn(['initial_approve_status', 'initial_approver_id','initial_approved_date']);
        });
    }
};
