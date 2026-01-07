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
        // STEP 1: Handle Renaming separately
        if (Schema::hasColumn('installments', 'amount_due')) {
            Schema::table('installments', function (Blueprint $table) {
                $table->renameColumn('amount_due', 'emi_amount');
            });
        }

        // STEP 2: Handle Modifications separately
        Schema::table('installments', function (Blueprint $table) {
            // Use change() on existing columns
            $table->decimal('amount_paid', 12, 2)->default(0)->change();
            
            // Note: Changing to ENUM can be tricky in some MySQL versions via change()
            // If this line fails, consider using a raw statement
            $table->enum('status', ['Pending', 'Partial', 'Paid', 'Overdue'])->default('Pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('installments', function (Blueprint $table) {
            // Reverse the renaming
            if (Schema::hasColumn('installments', 'emi_amount')) {
                $table->renameColumn('emi_amount', 'amount_due');
            }

            // Revert the status enum (matching your original SQL schema)
            // Original status was likely ['Pending', 'Paid', 'Overdue']
            $table->enum('status', ['Pending', 'Paid', 'Overdue'])->default('Pending')->change();
        });
    }
};