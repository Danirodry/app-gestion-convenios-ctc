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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->integer('documento');
            $table->string('nombre');
            $table->string('correo');
            $table->bigInteger('tel_cel');
            $table->string('direccion')->nullable();

            $table->foreignId('programas_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete(); 
                  
            $table->enum('estado_estudiante', ['completado', 'por_completar', 'cancelado'])
                  ->default('completado'); 
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
