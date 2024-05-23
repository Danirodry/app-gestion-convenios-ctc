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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nit');
            $table->string('n_convenio');
            $table->string('nombre');
            $table->bigInteger('tel_cel');
            $table->string('direccion')->nullable();
            $table->string('correo');
            $table->string('representante_legal');
            $table->enum('estado_empresa', ['completado', 'por_completar', 'cancelado'])
                  ->default('completado'); 
            $table->text('observaciones')->nullable();
            $table->date('inicio_convenio');
            $table->date('fin_convenio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
