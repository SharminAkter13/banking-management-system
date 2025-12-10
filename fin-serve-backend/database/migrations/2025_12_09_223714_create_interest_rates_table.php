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
    Schema::create('interest_rates', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('account_type_id')->constrained('account_types','id')->cascadeOnDelete();
        $table->decimal('rate', 5, 2);
        $table->date('effective_date');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interest_rates');
    }
};
