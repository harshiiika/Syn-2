<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();

            $table->string('student_name');
            $table->string('father_name')->nullable();

            // phone numbers as strings
            $table->string('father_contact', 20)->nullable();
            $table->string('father_whatsapp', 20)->nullable();
            $table->string('student_contact', 20)->nullable();

            // demographics / address
            $table->string('category', 50)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('branch_name', 100)->nullable();

            // flags
            $table->enum('ews', ['yes','no'])->default('no');
            $table->enum('service_background', ['yes','no'])->default('no');
            $table->enum('specially_abled', ['yes','no'])->default('no');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
