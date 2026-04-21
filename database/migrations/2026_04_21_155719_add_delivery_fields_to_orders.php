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
    Schema::table('orders', function (Blueprint $table) {
        $table->enum('delivery_type', ['pickup', 'delivery'])
              ->default('pickup')->after('notes');
        $table->decimal('customer_lat', 10, 7)->nullable()->after('delivery_type');
        $table->decimal('customer_lng', 10, 7)->nullable()->after('customer_lat');
    });

    Schema::create('courier_locations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->cascadeOnDelete();
        $table->decimal('lat', 10, 7);
        $table->decimal('lng', 10, 7);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
