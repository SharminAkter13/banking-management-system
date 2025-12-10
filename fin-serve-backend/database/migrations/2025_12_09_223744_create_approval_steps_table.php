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
    Schema::create('approval_steps', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('approval_id')->constrained('approvals','id')->cascadeOnDelete();
        $table->integer('step_number');
        $table->foreignId('role_id')->constrained('roles','id');
        $table->enum('status', ['Pending','Approved','Rejected'])->default('Pending');
        $table->boolean('required')->default(true);
        $table->dateTime('approved_at')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_steps');
    }
};
