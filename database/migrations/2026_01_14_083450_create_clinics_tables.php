<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cnpj', 18)->unique()->nullable();
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->string('city');
            $table->string('state', 2);
            $table->string('zip_code', 9);
            $table->time('business_hours_start')->default('08:00:00');
            $table->time('business_hours_end')->default('18:00:00');
            $table->decimal('default_session_price', 10, 2)->default(150.00);
            $table->integer('session_duration')->default(50);
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clinics');
    }
};