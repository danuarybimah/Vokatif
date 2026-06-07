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
        Schema::create('api_request_logs', function (Blueprint $table) {
    $table->id();

    $table->foreignId('api_client_id')->nullable()->constrained('api_clients')->nullOnDelete();

    $table->string('method');
    $table->string('endpoint');
    $table->string('ip_address')->nullable();
    $table->integer('status_code')->nullable();
    $table->dateTime('requested_at');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_request_logs');
    }
};
