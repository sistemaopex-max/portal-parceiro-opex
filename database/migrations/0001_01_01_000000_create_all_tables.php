<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role', 32)->default('partner');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->bigInteger('expiration')->index();
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->bigInteger('expiration')->index();
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedSmallInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('connection');
            $table->string('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();

            $table->index(['connection', 'queue', 'failed_at']);
        });

        Schema::create('categorias_parceiro', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('modificado_em')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('tipos_documento_empresa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias_parceiro')->cascadeOnDelete();
            $table->string('nome');
            $table->boolean('ativo')->default(true);
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('modificado_em')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['categoria_id', 'nome']);
        });

        Schema::create('parceiros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('categoria_id')->constrained('categorias_parceiro')->restrictOnDelete();
            $table->string('slug', 180)->unique();
            $table->string('razao_social');
            $table->string('cnpj', 14)->nullable()->unique();
            $table->string('telefone', 32)->nullable();
            $table->string('endereco', 500)->nullable();
            $table->string('email')->nullable();
            $table->string('cidade', 120)->nullable();
            $table->string('uf', 2)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('modificado_em')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('funcoes_funcionario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias_parceiro')->cascadeOnDelete();
            $table->string('nome');
            $table->boolean('ativo')->default(true);
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('modificado_em')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('tipos_documento_funcionario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funcao_funcionario_id')->constrained('funcoes_funcionario')->cascadeOnDelete();
            $table->string('nome');
            $table->boolean('ativo')->default(true);
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('modificado_em')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['funcao_funcionario_id', 'nome']);
        });

        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parceiro_id')->constrained('parceiros')->cascadeOnDelete();
            $table->foreignId('funcao_funcionario_id')->constrained('funcoes_funcionario')->restrictOnDelete();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->string('cpf', 11);
            $table->date('data_nascimento')->nullable();
            $table->boolean('documentacao_em_dia')->default(false);
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('modificado_em')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['parceiro_id', 'cpf']);
        });

        Schema::create('documentos_empresa', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('parceiro_id')->constrained('parceiros')->cascadeOnDelete();
            $table->foreignId('tipo_documento_empresa_id')->constrained('tipos_documento_empresa')->restrictOnDelete();
            $table->string('arquivo_disco', 32)->nullable();
            $table->string('arquivo_caminho', 512)->nullable();
            $table->string('arquivo_mime', 127)->nullable();
            $table->date('validade')->nullable();
            $table->string('status', 32)->default('faltando_documento');
            $table->foreignId('validado_por_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validado_em')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('modificado_em')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['parceiro_id', 'tipo_documento_empresa_id']);
        });

        Schema::create('documentos_funcionario', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('funcionario_id')->constrained('funcionarios')->cascadeOnDelete();
            $table->foreignId('tipo_documento_funcionario_id')->constrained('tipos_documento_funcionario')->restrictOnDelete();
            $table->string('arquivo_disco', 32)->nullable();
            $table->string('arquivo_caminho', 512)->nullable();
            $table->string('arquivo_mime', 127)->nullable();
            $table->date('validade')->nullable();
            $table->string('status', 32)->default('faltando_documento');
            $table->foreignId('validado_por_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validado_em')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('modificado_em')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['funcionario_id', 'tipo_documento_funcionario_id']);
        });

        Schema::create('convites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criado_por')->constrained('users')->cascadeOnDelete();
            $table->foreignId('categoria_id')->constrained('categorias_parceiro')->restrictOnDelete();
            $table->string('email');
            $table->string('token', 64)->unique();
            $table->timestamp('expira_em');
            $table->timestamp('usado_em')->nullable();
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('modificado_em')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('convites');
        Schema::dropIfExists('documentos_funcionario');
        Schema::dropIfExists('documentos_empresa');
        Schema::dropIfExists('funcionarios');
        Schema::dropIfExists('tipos_documento_funcionario');
        Schema::dropIfExists('funcoes_funcionario');
        Schema::dropIfExists('parceiros');
        Schema::dropIfExists('tipos_documento_empresa');
        Schema::dropIfExists('categorias_parceiro');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
