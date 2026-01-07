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
    Schema::table('loans', function (Blueprint $table) {
        $table->decimal('interest_rate', 5,2)->after('principal_amount');
        $table->decimal('emi_amount',12,2)->nullable()->after('interest_rate');
        $table->decimal('total_payable',12,2)->nullable()->after('emi_amount');
        $table->decimal('remaining_balance',12,2)->nullable()->after('total_payable');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            //
        });
    }
};
