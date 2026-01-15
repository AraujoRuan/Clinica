<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('cpf', 14)->unique()->nullable();
            $table->date('birth_date');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widower'])->nullable();
            $table->string('profession')->nullable();
            $table->text('emergency_contact')->nullable();
            $table->text('medical_history')->nullable();
            $table->text('current_medication')->nullable();
            $table->text('anamnesis')->nullable();
            $table->enum('status', ['active', 'inactive', 'discharged'])->default('active');
            $table->foreignId('professional_id')->constrained('users');
            $table->foreignId('clinic_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['professional_id', 'status']);
            $table->index('code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
};