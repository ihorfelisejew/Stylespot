<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('shipping_method');
            $table->string('shipping_city');
            $table->string('post_office_number')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->enum('status', ['not_processed', 'processing', 'shipped', 'completed'])->default('not_processed');
            $table->decimal('total_price', 10, 2);
            // Додаємо timestamps
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
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
