<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('funcoes_funcionario', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('nome');
        });

        Schema::table('tipos_documento_empresa', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('nome');
        });

        Schema::table('tipos_documento_funcionario', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('nome');
        });

        // Popula slugs para registros existentes
        foreach (DB::table('funcoes_funcionario')->orderBy('id')->get() as $row) {
            $slug = $this->gerarSlug($row->nome, 'funcoes_funcionario', $row->id);
            DB::table('funcoes_funcionario')->where('id', $row->id)->update(['slug' => $slug]);
        }

        foreach (DB::table('tipos_documento_empresa')->orderBy('id')->get() as $row) {
            $slug = $this->gerarSlug($row->nome, 'tipos_documento_empresa', $row->id);
            DB::table('tipos_documento_empresa')->where('id', $row->id)->update(['slug' => $slug]);
        }

        foreach (DB::table('tipos_documento_funcionario')->orderBy('id')->get() as $row) {
            $slug = $this->gerarSlug($row->nome, 'tipos_documento_funcionario', $row->id);
            DB::table('tipos_documento_funcionario')->where('id', $row->id)->update(['slug' => $slug]);
        }

        Schema::table('funcoes_funcionario', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });

        Schema::table('tipos_documento_empresa', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });

        Schema::table('tipos_documento_funcionario', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('funcoes_funcionario', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('tipos_documento_empresa', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('tipos_documento_funcionario', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    private function gerarSlug(string $nome, string $tabela, int $ignoreId): string
    {
        $base = Str::slug($nome) ?: 'item';
        $candidate = $base;
        $i = 2;

        while (DB::table($tabela)->where('slug', $candidate)->where('id', '!=', $ignoreId)->exists()) {
            $candidate = $base . '-' . $i++;
        }

        return $candidate;
    }
};
