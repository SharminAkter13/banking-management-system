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
    Schema::create('loans', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('customer_id')->constrained('customers','id')->cascadeOnDelete();
        $table->foreignId('branch_id')->constrained('branches','id');
        $table->foreignId('loan_type_id')->constrained('loan_types','id');
        $table->decimal('principal_amount', 12, 2);
        $table->dateTime('issued_date')->nullable();
        $table->integer('duration_months');
        $table->enum('status', ['Pending','Active','Closed','Rejected','Defaulted'])->default('Pending');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
