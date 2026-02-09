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
        Schema::create('bank_info', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name_one');
            $table->string('account_number_one');
            $table->string('account_holder_one');
            $table->string('bank_name_two')->nullable();
            $table->string('account_number_two')->nullable();
            $table->string('account_holder_two')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_info');
    }
};
