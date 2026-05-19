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
        Schema::create('employee_salary_assignment_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_salary_assignment_id')
                ->constrained('employee_salary_assignments')
                ->onDelete('cascade')
                ->name('fk_esa_components_assignment'); // Use shorter name if needed, but defaults are fine usually
            $table->foreignId('salary_component_id')
                ->constrained('salary_components')
                ->onDelete('cascade')
                ->name('fk_esa_components_component');
            $table->decimal('amount', 15, 2)->default(0);
            $table->boolean('is_editable')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary_assignment_components');
    }
};
