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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('employeeID');
            $table->string('full_name');
            $table->string('phonenumber');
            $table->integer('reporting_to')->nullable();
            $table->string('personal_email')->unique();
            $table->string('gender')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('qualification')->nullable();
            $table->string('esi_no')->unique()->nullable();
            $table->bigInteger('aadhaar')->unique()->nullable();
            $table->bigInteger('pf_no')->unique()->nullable();
            $table->string('electoral_id')->unique()->nullable();
            $table->string('pan')->unique()->nullable();
            $table->date('dob')->nullable();
            $table->string('group')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('mobile_number')->unique()->nullable();
            $table->string('mobile_relationship')->nullable();
            $table->string('landline')->unique()->nullable();
            $table->string('landline_relationship')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('designation_id')->nullable();
            $table->date('join_date')->nullable();
            $table->tinyInteger('shift_id')->nullable();
            $table->tinyInteger('role')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('login_limited_time')->nullable();
            $table->string('appointment_status')->nullable();
            $table->tinyInteger('team_lead')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('account_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
