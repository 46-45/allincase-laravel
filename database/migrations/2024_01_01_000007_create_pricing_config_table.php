<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_config', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('base_price', 12, 2);
            $table->decimal('base_km', 5, 2)->default(5.00);
            $table->decimal('price_per_km', 10, 2);
            $table->decimal('service_fee', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_config');
    }
};
