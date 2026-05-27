<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'papel') && ! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('papel', 'role');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'role') && ! Schema::hasColumn('users', 'papel')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('role', 'papel');
            });
        }
    }
};
