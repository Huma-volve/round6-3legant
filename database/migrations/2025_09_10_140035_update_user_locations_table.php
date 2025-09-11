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
        Schema::table('user_locations', function (Blueprint $table) {
            // تعديل الأعمدة
            $table->string('fname', 50)->change();
            $table->string('lname', 50)->change();
            $table->string('st_address', 255)->change();
            $table->string('state', 100)->change();
            $table->string('country', 100)->change();
            $table->string('postal_code', 20)->change();
            $table->string('phone_no', 20)->change();
            $table->enum('address_type', ['shipping_address', 'villing_address', 'both'])->nullable()->change();
    
            // إضافة فهارس جديدة
            $table->index(['state', 'country']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_locations', function (Blueprint $table) {
            $table->string('address_type')->nullable()->change();

            $table->dropIndex(['state', 'country']);
        });
    }
};
