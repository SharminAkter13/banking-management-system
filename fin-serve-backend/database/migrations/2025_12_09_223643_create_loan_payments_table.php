<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('loan_payments', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('loan_id')->constrained('loans','id')->cascadeOnDelete();
        $table->foreignId('transaction_id')->constrained('transactions','id')->cascadeOnDelete();
        $table->decimal('amount', 12, 2);
        $table->dateTime('payment_date');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payments');
    }
};
