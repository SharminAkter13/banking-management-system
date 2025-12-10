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
    Schema::create('kyc_forms', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
        
        // Identity
        $table->string('first_name');
        $table->string('last_name');
        $table->string('father_first_name')->nullable();
        $table->string('father_last_name')->nullable();
        $table->enum('gender', ['Male', 'Female']);
        $table->enum('marital_status', ['Single', 'Married']);
        $table->date('dob');
        $table->string('nationality');
        $table->enum('status_type', ['Resident Individual', 'Non Resident', 'Foreign National']);
        $table->string('pan')->nullable();
        $table->string('proof_identity')->nullable();

        // Address
        $table->string('address_line1');
        $table->string('address_line2')->nullable();
        $table->string('city');
        $table->string('state');
        $table->string('country');
        $table->string('postal_code');

        $table->string('phone');
        $table->string('email')->nullable();

        // Address proof
        $table->json('proof_address')->nullable();

        // File Uploads
        $table->json('documents')->nullable();

        // Declaration
        $table->string('signature_path')->nullable();
        $table->date('signed_date');

        // KYC status
        $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_forms');
    }
};
