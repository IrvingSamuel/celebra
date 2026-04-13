<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gift_pledges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gift_item_id')->constrained()->cascadeOnDelete();
            $table->string('giver_name');
            $table->string('giver_email');
            $table->string('giver_phone')->nullable();
            $table->string('cancel_token', 80)->unique();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_pledges');
    }
};
