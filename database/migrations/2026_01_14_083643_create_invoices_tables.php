<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('professional_id')->constrained('users');
            $table->foreignId('appointment_id')->nullable()->constrained();
            $table->foreignId('clinic_id')->constrained();
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('fine', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->date('issue_date')->default(now());
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled', 'partial'])->default('pending');
            $table->enum('payment_method', ['cash', 'credit_card', 'debit_card', 'pix', 'bank_slip', 'transfer'])->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'due_date']);
            $table->index(['professional_id', 'issue_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};