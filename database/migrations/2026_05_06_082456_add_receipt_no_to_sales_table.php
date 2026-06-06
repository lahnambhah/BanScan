<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('receipt_no')->nullable()->after('id');
        });

        // Generate receipt numbers for old sales
        $sales = DB::table('sales')->get();

        foreach ($sales as $sale) {
            DB::table('sales')
                ->where('id', $sale->id)
                ->update([
                    'receipt_no' => 'BS-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5))
                ]);
        }

        // Make unique after values exist
        Schema::table('sales', function (Blueprint $table) {
            $table->unique('receipt_no');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropUnique(['receipt_no']);
            $table->dropColumn('receipt_no');
        });
    }
};