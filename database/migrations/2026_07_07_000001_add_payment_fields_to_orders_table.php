<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('momo_phone')->nullable()->after('payment_method');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending')->after('momo_phone');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['momo_phone', 'payment_status']);
        });
    }
};
