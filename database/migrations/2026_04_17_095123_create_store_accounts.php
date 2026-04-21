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
        Schema::create('store_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name');         // BCA, BRI, GoPay, dll
            $table->string('account_number');
            $table->string('account_holder');
            $table->string('type');              // bank_transfer | e_wallet | cod
            $table->string('logo')->nullable();  // opsional
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_accounts');
    }
};
