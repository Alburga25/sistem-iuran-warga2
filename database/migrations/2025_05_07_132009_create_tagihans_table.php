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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('iuran_id')->constrained()->onDelete('cascade');
            $table->decimal('jumlah_bayar', 12, 2)->nullable();
            $table->date('tanggal_jatuh_tempo');
            $table->enum('status', ['belum_bayar', 'sudah_bayar'])->default('belum_bayar');
            $table->string('order_id')->nullable();  // Menambahkan kolom order_id untuk Midtrans
            $table->string('snap_token')->nullable(); // Menambahkan kolom snap_token untuk Midtrans
            $table->timestamp('settlement_time')->nullable(); // kolom tambahan untuk menyimpan waktu pembayaran dari Midtrans
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
