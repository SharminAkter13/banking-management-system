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
    Schema::create('accounts', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('customer_id')->constrained('customers','id')->cascadeOnDelete();
        $table->foreignId('branch_id')->constrained('branches','id')->cascadeOnDelete();
        $table->foreignId('account_type_id')->constrained('account_types','id');
        $table->foreignId('account_status_id')->constrained('account_status','id');
        $table->decimal('balance', 12, 2)->default(0);
        $table->dateTime('opened_date');
        $table->dateTime('closed_date')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
