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
    Schema::create('approval_actions', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('approval_step_id')->constrained('approval_steps','id')->cascadeOnDelete();
        $table->foreignId('approved_by')->constrained('users','id')->cascadeOnDelete();
        $table->enum('action', ['Approved','Rejected']);
        $table->text('comments')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_actions');
    }
};
