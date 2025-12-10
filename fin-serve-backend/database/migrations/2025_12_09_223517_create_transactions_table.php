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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('account_id')->constrained('accounts','account_id')->cascadeOnDelete();
        $table->foreignId('transaction_type_id')->constrained('transaction_types','id');
        $table->foreignId('transaction_status_id')->constrained('transaction_status','id');
        $table->decimal('amount', 12, 2);
        $table->dateTime('transaction_date');
        $table->foreignId('created_by')->constrained('users','user_id');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
