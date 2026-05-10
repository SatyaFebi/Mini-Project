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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('ID_NOTA')->unique()->index();
            $table->date('TGL')->index();
            $table->string('KODE_PELANGGAN')->index();
            $table->foreign('KODE_PELANGGAN')->references('ID_PELANGGAN')->on('pelanggans')->cascadeOnDelete();
            $table->integer('SUBTOTAL')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
