<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_items', function (Blueprint $table) {
        $table->id();

        $table->foreignId('return_id')->constrained()->cascadeOnDelete();
        $table->foreignId('item_id')->nullable()->constrained()->nullOnDelete();

        $table->foreignId('sale_id')->nullable()->constrained()->nullOnDelete(); 

        $table->integer('quantity');
        $table->decimal('price', 10, 2);

        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_items'); 
    }
};