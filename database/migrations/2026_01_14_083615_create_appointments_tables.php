<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('professional_id')->constrained('users');
            $table->foreignId('clinic_id')->constrained();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('status', ['scheduled', 'confirmed', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->enum('type', ['consultation', 'evaluation', 'follow_up', 'other'])->default('consultation');
            $table->text('notes')->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamps();
            
            $table->index(['professional_id', 'start_time']);
            $table->unique(['professional_id', 'start_time'], 'unique_professional_time');
            $table->index(['status', 'start_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};