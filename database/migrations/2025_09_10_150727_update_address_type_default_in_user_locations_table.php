<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_locations', function (Blueprint $table) {
            $table->enum('address_type', ['shipping_address', 'billing_address', 'both'])
                  ->default('both')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('user_locations', function (Blueprint $table) {
            $table->enum('address_type', ['shipping_address', 'billing_address', 'both'])
                  ->nullable()
                  ->change();
        });
    }
};
