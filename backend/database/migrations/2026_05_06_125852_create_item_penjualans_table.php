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
            $table->string('NOTA')->index();
            $table->foreign('NOTA')->references('ID_NOTA')->on('penjualans')->cascadeOnDelete();
            $table->string('KODE_BARANG')->index();
            $table->foreign('KODE_BARANG')->references('KODE')->on('barangs')->cascadeOnDelete();
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
