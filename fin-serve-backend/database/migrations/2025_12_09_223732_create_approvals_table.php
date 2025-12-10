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
    Schema::create('approvals', function (Blueprint $table) {
        $table->id('id');
        $table->string('entity_type');        // loan, transaction, account
        $table->unsignedBigInteger('entity_id');
        $table->integer('current_step')->default(1);
        $table->enum('status', ['Pending','In Progress','Approved','Rejected'])->default('Pending');
        $table->foreignId('created_by')->constrained('users','id');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
