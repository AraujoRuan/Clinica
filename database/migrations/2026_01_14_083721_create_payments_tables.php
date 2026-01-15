<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained();
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->enum('method', ['cash', 'credit_card', 'debit_card', 'pix', 'bank_slip', 'transfer']);
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['invoice_id', 'payment_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};