<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained()->comment('Responsável pelo lançamento');
            $table->string('description');
            $table->enum('category', ['rent', 'utilities', 'supplies', 'salary', 'taxes', 'equipment', 'marketing', 'other']);
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->string('provider')->nullable();
            $table->string('invoice_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['category', 'due_date']);
            $table->index(['clinic_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};