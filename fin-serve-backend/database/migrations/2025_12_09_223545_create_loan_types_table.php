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
    Schema::create('loan_types', function (Blueprint $table) {
        $table->id('id');
        $table->string('name');
        $table->string('description')->nullable();
        $table->decimal('interest_rate', 5, 2);
        $table->decimal('max_amount', 12, 2)->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_types');
    }
};
