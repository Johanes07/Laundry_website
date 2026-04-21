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
        Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_code')->unique(); // misal: LDR-20250417-001
        $table->string('customer_name');
        $table->string('customer_phone');
        $table->string('customer_email')->nullable();
        $table->text('customer_address');
        $table->foreignId('service_id')->constrained();
        $table->decimal('quantity', 8, 2);     // berat dalam kg
        $table->decimal('total_price', 10, 2)->nullable();
        $table->text('notes')->nullable();
        $table->enum('status', [
            'pending',      // baru masuk
            'confirmed',    // dikonfirmasi admin
            'processing',   // sedang dicuci
            'ready',        // siap diambil
            'completed',    // sudah diambil
            'cancelled'     // dibatalkan
        ])->default('pending');
        $table->date('estimated_done')->nullable();
        $table->timestamp('picked_up_at')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
