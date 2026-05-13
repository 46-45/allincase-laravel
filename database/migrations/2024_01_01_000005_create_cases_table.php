<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('case_number', 50)->unique();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('lawyer_id')->nullable();
            $table->unsignedBigInteger('category_id');

            // Meeting location
            $table->decimal('meeting_lat', 10, 8);
            $table->decimal('meeting_lng', 11, 8);
            $table->text('meeting_address');
            $table->dateTime('meeting_datetime');

            // Administrative area
            $table->string('meeting_sub_district', 100)->nullable();
            $table->string('meeting_city_district', 100)->nullable();
            $table->string('meeting_province', 100)->nullable();

            // Radius matching
            $table->smallInteger('current_radius_level')->default(1);
            $table->dateTime('radius_expanded_at')->nullable();

            // Lawyer position when accepted
            $table->decimal('lawyer_lat_on_accept', 10, 8)->nullable();
            $table->decimal('lawyer_lng_on_accept', 11, 8)->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();

            // Pricing
            $table->decimal('base_price', 12, 2)->nullable();
            $table->decimal('service_fee', 12, 2)->nullable();
            $table->decimal('total_price', 12, 2)->nullable();

            // Status
            $table->enum('status', [
                'pending', 'matched', 'waiting_payment', 'paid',
                'in_progress', 'completed', 'cancelled', 'expired'
            ])->default('pending');

            $table->text('detail_notes')->nullable();
            $table->string('payment_id', 255)->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();

            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('users');
            $table->foreign('lawyer_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
