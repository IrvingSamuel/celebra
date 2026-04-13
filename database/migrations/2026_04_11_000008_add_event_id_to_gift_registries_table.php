<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gift_registries', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->after('wedding_id')->constrained()->nullOnDelete();
            $table->string('slug')->nullable()->after('title');
            $table->boolean('active')->default(true)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('gift_registries', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn(['event_id', 'slug', 'active']);
        });
    }
};
