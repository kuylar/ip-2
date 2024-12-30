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
        Schema::dropIfExists('user_addresses');
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained("users");
            $table->foreignId('sehir_id')->constrained("sehirs");
            $table->foreignId('ilce_id')->constrained("ilces");
            $table->foreignId('mahalle_id')->constrained("mahalles");
            $table->text('adress');
            $table->timestamps();
        });
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained("users");
            $table->text('address');
            $table->text('address2');
            $table->text('city');
            $table->text('town');
            $table->integer('zipcode');
            $table->timestamps();
        });
    }
};
