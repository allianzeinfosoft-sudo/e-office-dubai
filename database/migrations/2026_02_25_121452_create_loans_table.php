<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('loan_type'); // logic for loan vs advance
            $table->decimal('amount', 15, 2);
            $table->decimal('emi_amount', 15, 2);
            $table->integer('total_installments');
            $table->integer('paid_installments')->default(0);
            $table->date('disbursement_date');
            $table->string('status')->default('active'); // active, completed, cancelled
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
