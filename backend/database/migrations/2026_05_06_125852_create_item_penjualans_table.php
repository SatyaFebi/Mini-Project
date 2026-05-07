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
        Schema::create('item_penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('NOTA');
            $table->foreignId('KODE_BARANG')->constrained('barangs')->cascadeOnDelete();
            $table->integer('Qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_penjualans');
    }
};
