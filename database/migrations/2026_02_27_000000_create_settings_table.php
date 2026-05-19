<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
     /**
      * Run the migrations.
      */
     public function up(): void
     {
          Schema::create('settings', function (Blueprint $header) {
               $header->id();
               $header->string('key')->unique();
               $header->text('value')->nullable();
               $header->string('type')->default('string');
               $header->timestamps();
          });

          // Insert default setting for part-wise salary
          DB::table('settings')->insert([
               [
                    'key' => 'enable_part_wise_salary',
                    'value' => '0',
                    'type' => 'boolean',
                    'created_at' => now(),
                    'updated_at' => now(),
               ],
               [
                    'key' => 'company_name',
                    'value' => 'Allianze Infosoft',
                    'type' => 'string',
                    'created_at' => now(),
                    'updated_at' => now(),
               ],
               [
                    'key' => 'company_address',
                    'value' => '123 Tech Park, Silicon Valley, CA 94025',
                    'type' => 'string',
                    'created_at' => now(),
                    'updated_at' => now(),
               ],
               [
                    'key' => 'company_logo',
                    'value' => 'assets/img/branding/logo.png',
                    'type' => 'string',
                    'created_at' => now(),
                    'updated_at' => now(),
               ],
          ]);
     }

     /**
      * Reverse the migrations.
      */
     public function down(): void
     {
          Schema::dropIfExists('settings');
     }
};
