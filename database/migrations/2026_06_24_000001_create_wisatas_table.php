<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wisatas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_wisata');
            $table->text('deskripsi');
            // decimal(10,8) → cukup presisi untuk koordinat GPS (-90 s/d 90)
            $table->decimal('latitude', 10, 8);
            // decimal(11,8) → cukup presisi untuk koordinat GPS (-180 s/d 180)
            $table->decimal('longitude', 11, 8);
            $table->string('image')->nullable();
            // FK ke tabel users: jika user dihapus, data wisatanya ikut terhapus
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wisatas');
    }
};
