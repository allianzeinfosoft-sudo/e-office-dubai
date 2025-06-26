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
        Schema::create('asset_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_code', 50);
            $table->string('vendor_name', 255);
            $table->integer('vendor_category')->nullable();
            $table->text('vendor_address')->nullable(); 
            $table->string('email', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('contact_person', 255)->nullable();
            $table->string('contact_number', 15)->nullable();
            $table->string('mobile_number', 15)->nullable();
            $table->integer('status')->default(1)->comment('0 = Inactive, 1 = Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_vendors');
    }
};
