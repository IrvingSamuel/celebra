<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Passo 1: adiciona 'ambos' ao enum sem remover 'both' ainda
        DB::statement("ALTER TABLE venues MODIFY COLUMN type ENUM('indoor', 'outdoor', 'both', 'ambos') NOT NULL DEFAULT 'indoor'");
        // Passo 2: migra os registros existentes
        DB::table('venues')->where('type', 'both')->update(['type' => 'ambos']);
        // Passo 3: remove 'both' do enum
        DB::statement("ALTER TABLE venues MODIFY COLUMN type ENUM('indoor', 'outdoor', 'ambos') NOT NULL DEFAULT 'indoor'");
    }

    public function down(): void
    {
        DB::table('venues')->where('type', 'ambos')->update(['type' => 'both']);
        DB::statement("ALTER TABLE venues MODIFY COLUMN type ENUM('indoor', 'outdoor', 'both') NOT NULL DEFAULT 'indoor'");
    }
};
