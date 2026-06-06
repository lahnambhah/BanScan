<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('return_items', function (Blueprint $table) {

            $table->foreignId('sale_item_id')
                ->nullable()
                ->after('return_id')
                ->constrained('sale_items')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('return_items', function (Blueprint $table) {

            $table->dropForeign(['sale_item_id']);
            $table->dropColumn('sale_item_id');

        });
    }
};