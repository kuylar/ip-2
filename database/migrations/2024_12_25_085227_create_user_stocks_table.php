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
        Schema::create('user_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('stock_id')->constrained('stocks');
            $table->enum('transaction_type', ["withdraw", "deposit", "buy", "sell"]);
            $table->integer("stock_amount");
            $table->float("base_money_amount")->comment("Alınan tarihteki fiyat");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_stocks');
    }
};
