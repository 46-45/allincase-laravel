<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lawyer_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('bar_number', 100)->nullable();
            $table->text('specializations')->nullable(); // JSON array of category_id
            $table->integer('years_of_experience')->default(0);
            $table->text('bio')->nullable();
            $table->boolean('is_available')->default(false);

            // Location
            $table->decimal('last_lat', 10, 8)->nullable();
            $table->decimal('last_lng', 11, 8)->nullable();
            $table->timestamp('last_location_at')->nullable();

            // Administrative area
            $table->string('sub_district', 100)->nullable();
            $table->string('city_district', 100)->nullable();
            $table->string('province', 100)->nullable();

            // Bank info
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_account_name', 255)->nullable();

            // Stats
            $table->decimal('rating_avg', 3, 2)->default(0.00);
            $table->integer('total_cases')->default(0);
            $table->decimal('total_earned', 14, 2)->default(0.00);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lawyer_profiles');
    }
};
