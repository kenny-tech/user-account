<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_type');
            $table->integer('amount');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('account_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');  
            $table->foreign('account_id')->references('id')->on('accounts');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_logs');
    }
};
