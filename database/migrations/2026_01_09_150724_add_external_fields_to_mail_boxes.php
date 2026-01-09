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
        Schema::table('mail_boxes', function (Blueprint $table) {
            //
            $table->string('external_email_id')->nullable()->unique();
            $table->string('external_from')->nullable();
            $table->string('external_date')->nullable();
            $table->longText('raw_headers')->nullable();
            // Convert attachments to JSON
            $table->json('attachments')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mail_boxes', function (Blueprint $table) {
            //
            $table->dropColumn('external_email_id');
            $table->dropColumn('external_from');
            $table->dropColumn('external_date');
            $table->dropColumn('raw_headers');
            // Revert attachments to text
            $table->text('attachments')->nullable()->change();
        });
    }
};
