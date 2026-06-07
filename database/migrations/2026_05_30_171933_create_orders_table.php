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
    $table->string('invoice_number')->unique();

    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();

    $table->decimal('total_amount', 12, 2);
    $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired', 'refunded'])->default('pending');
    $table->enum('payment_method', ['manual', 'qris', 'bank_transfer', 'ewallet'])->default('manual');

    $table->dateTime('paid_at')->nullable();
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
