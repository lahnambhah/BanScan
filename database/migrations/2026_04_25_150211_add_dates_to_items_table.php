<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->date('manufactured_at')->nullable();
            $table->date('expiry_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
};
