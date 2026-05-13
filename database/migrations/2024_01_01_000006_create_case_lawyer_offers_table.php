<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_lawyer_offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('case_id');
            $table->unsignedBigInteger('lawyer_id');
            $table->enum('status', ['sent', 'accepted', 'rejected', 'expired'])->default('sent');
            $table->timestamp('sent_at')->useCurrent();
            $table->dateTime('responded_at')->nullable();

            $table->unique(['case_id', 'lawyer_id'], 'uq_case_lawyer');
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
            $table->foreign('lawyer_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_lawyer_offers');
    }
};
