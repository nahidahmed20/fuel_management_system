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
        Schema::create('mobil_buys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('quantity', 15, 3);
            $table->decimal('buying_price', 15, 3);
            $table->decimal('total_buying_price', 15, 3);
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobil_buys');
    }
};
