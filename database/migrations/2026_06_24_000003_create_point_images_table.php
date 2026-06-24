<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('point_id')->constrained('points')->onDelete('cascade');
            $table->string('filename');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_images');
    }
};
