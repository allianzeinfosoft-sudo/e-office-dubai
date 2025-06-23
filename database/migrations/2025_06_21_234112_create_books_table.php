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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no');
            $table->string('title');
            $table->string('author');
            $table->integer('category_id');
            $table->text('description')->nullable();
            $table->string('cover')->nullable();
            $table->integer('status')->default(0)->comments('0 = In Stock, 1 = Issued, 2 = Damaged, 3 = Lost'); // 0: Available, 1: Unavailable, 2: Lent
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
