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
        Schema::table('employees', function (Blueprint $table) {
            $table->boolean('open_work_status')->default(false)->after('login_limited_time');
            $table->date('open_work_setdate')->nullable()->after('open_work_status');
            $table->string('ifsc', 20)->nullable()->after('beneficiary_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['open_work_status', 'open_work_setdate', 'ifsc']);
        });
    }
};
