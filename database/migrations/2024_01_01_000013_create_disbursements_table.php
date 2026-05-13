<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disbursements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('case_id')->unique();
            $table->unsignedBigInteger('lawyer_id');
            $table->decimal('amount', 12, 2);
            $table->decimal('platform_cut', 12, 2)->default(0);
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_account_name', 255)->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('iris_transfer_id', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->dateTime('processed_at')->nullable();

            $table->foreign('case_id')->references('id')->on('cases');
            $table->foreign('lawyer_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disbursements');
    }
};
