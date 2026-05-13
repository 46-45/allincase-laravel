<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('case_id')->unique();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('lawyer_id');
            $table->boolean('is_satisfied');
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('case_id')->references('id')->on('cases');
            $table->foreign('client_id')->references('id')->on('users');
            $table->foreign('lawyer_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
