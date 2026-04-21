<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable(); // transfer | cod | e_wallet
            $table->foreignId('store_account_id')->nullable()->constrained('store_accounts');
            $table->string('payment_status')->default('unpaid'); // unpaid | paid | cod
            $table->timestamp('paid_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_status',
                'paid_at'
            ]);

            $table->dropConstrainedForeignId('store_account_id');
        });
    }
};
