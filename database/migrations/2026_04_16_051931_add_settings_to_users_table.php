<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo')->nullable();

            $table->boolean('notify_low_stock')->default(true);
            $table->boolean('notify_sales')->default(true);
            $table->boolean('notify_new_items')->default(true);
            $table->boolean('notify_email')->default(true);
        });
    }


    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
