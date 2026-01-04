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
        Schema::create('fuel_outs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fuel_type_id');
            $table->unsignedBigInteger('nozzle_id');
            $table->decimal('quantity', 15, 3);
            $table->decimal('total_sell', 15, 3);
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_outs');
    }
};
