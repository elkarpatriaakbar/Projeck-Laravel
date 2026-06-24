<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('geojson_files', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                        // nama tampilan
            $table->enum('type', ['boundary', 'polyline', 'polygon']);    // jenis layer
            $table->string('filename');                                    // nama file di storage
            $table->string('color')->default('#3388ff');                  // warna layer di peta
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('geojson_files');
    }
};
