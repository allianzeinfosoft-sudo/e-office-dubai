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
<<<<<<< HEAD:database/migrations/2025_04_24_115309_add_new_columns_in_mail_boxes_table.php
        Schema::table('mail_boxes', function (Blueprint $table) {
            //
            $table->integer('mark_as_read')->nullable()->default(0)->comments('0 = Not read, 1 = Yes Read');
=======
        Schema::create('appearences', function (Blueprint $table) {
            $table->id();
            $table->string('background_type');
            $table->string('image');
            $table->timestamps();
>>>>>>> anil_eoffice:database/migrations/2025_04_25_153641_create_appearences_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<< HEAD:database/migrations/2025_04_24_115309_add_new_columns_in_mail_boxes_table.php
        Schema::table('mail_boxes', function (Blueprint $table) {
            //
            drop_column('mail_boxes', 'mark_as_read');
        });
=======
        Schema::dropIfExists('appearences');
>>>>>>> anil_eoffice:database/migrations/2025_04_25_153641_create_appearences_table.php
    }
};
