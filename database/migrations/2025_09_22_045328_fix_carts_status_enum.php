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
        Schema::table('carts', function (Blueprint $table) {
            // Drop the existing status column
            $table->dropColumn('status');
        });
        
        Schema::table('carts', function (Blueprint $table) {
            // Recreate the status column with proper enum values
            $table->enum('status', ['Draft', 'Menunggu Persetujuan', 'Disetujui', 'Ditolak', 'Stok Kosong'])
                  ->default('Draft')
                  ->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Drop the status column
            $table->dropColumn('status');
        });
        
        Schema::table('carts', function (Blueprint $table) {
            // Restore the original status column
            $table->enum('status', ['Menunggu Persetujuan', 'Disetujui', 'Ditolak', 'Stok Kosong'])
                  ->default('Menunggu Persetujuan')
                  ->after('quantity');
        });
    }
};
