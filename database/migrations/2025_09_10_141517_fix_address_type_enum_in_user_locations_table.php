<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تعديل القيم الخاطئة الموجودة في البيانات أولًا
        DB::table('user_locations')
            ->where('address_type', 'villing_address')
            ->update(['address_type' => 'billing_address']);

        // تعديل الـ enum نفسه
        Schema::table('user_locations', function (Blueprint $table) {
            $table->enum('address_type', ['shipping_address', 'billing_address', 'both'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إذا حبيت ترجع للقيمة السابقة
        DB::table('user_locations')
            ->where('address_type', 'billing_address')
            ->update(['address_type' => 'villing_address']);

        Schema::table('user_locations', function (Blueprint $table) {
            $table->enum('address_type', ['shipping_address', 'villing_address', 'both'])->nullable()->change();
        });
    }
};
