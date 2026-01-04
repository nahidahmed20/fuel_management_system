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
        Schema::create('nozzle_meters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nozzle_id');
            $table->decimal('prev_meter', 15, 3)->default(0);
            $table->decimal('curr_meter', 15, 3)->default(0);
            $table->decimal('sold_quantity', 15, 3)->default(0);
            $table->date('date')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nozzle_meters');
    }
};
