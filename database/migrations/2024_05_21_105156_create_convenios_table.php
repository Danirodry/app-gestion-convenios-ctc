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
        Schema::create('convenios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresas_id')->constrained() ->cascadeOnUpdate() ->cascadeOnDelete();
            $table->foreignId('estudiantes_id')->constrained() ->cascadeOnUpdate()  ->cascadeOnDelete();
            $table->enum('estado_convenio', ['completado', 'por_completar', 'cancelado'])
                  ->default('completado'); 
            $table->text('observaciones')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convenios');
    }
};
