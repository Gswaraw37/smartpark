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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_status', ['Paid', 'Pending', 'Failed'])->default('Pending');
            $table->string('payment_method', 50);
            $table->string('transaction_id', 100);
            $table->string('snap_token', 255)->nullable();
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('parking_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
