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
        Schema::create('recruitments', function (Blueprint $table) {
            $table->id();
            $table->integer('empId')->nullable();
            $table->date('rrfDate')->nullable();
            $table->integer('branchId')->nullable();
            $table->integer('departmentId')->nullable();
            $table->integer('positionId')->nullable();
            $table->integer('projectId')->nullable();
            $table->integer('shiftId')->nullable();
            $table->string('salaryRange', 100)->nullable();
            $table->integer('jobType')->nullable();
            $table->integer('interviewer')->nullable();        
            $table->integer('sittingArragement')->nullable();        
            $table->integer('minimumQualification')->nullable();
            $table->integer('skillRequired')->nullable();        
            $table->string('experience',100)->nullable();        
            $table->string('schoolingMedium',100)->nullable();        
            $table->string('graduation',100)->nullable();        
            $table->string('ageGroup',100)->nullable();        
            $table->string('location',150)->nullable();        
            $table->string('interviewPlace',150)->nullable();        
            $table->integer('priority')->nullable()->comment('0= N/A, 1 = High (Within 7 days), 2 = Medium (Within 10-15 days), 3 = Low (Within 30 days)')->default(0);
            $table->integer('referral')->nullable()->comment('0 = No, 1 = Yes, 2 = Not Applicable')->default(0);        
            $table->integer('referralIncentive')->nullable()->comment('0 = No, 1 = Yes, 2 = Not Applicable')->default(0);
            $table->integer('requireToAndFroCharge')->nullable()->comment('0 = No, 1 = Yes, 2 = Not Applicable')->default(0);
            $table->integer('keyword')->nullable();
            $table->integer('seekApproval')->nullable();
            $table->string('jobTitle', 255)->nullable();
            $table->text('jobDescription')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitments');
    }
};
