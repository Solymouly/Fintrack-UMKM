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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Menyambungkan transaksi dengan user yang login
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
                    
            // Kolom inputan sesuai spesifikasi
            $table->enum('type', ['pemasukan', 'pengeluaran']); 
            $table->string('category');
            $table->decimal('amount', 15, 2); 
            $table->date('transaction_date');
            $table->text('description')->nullable(); 
                    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
