<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSebPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seb_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('payment_id');
            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
                ->onDelete('CASCADE');

            $table->tinyInteger('method')->default(1);
            $table->tinyInteger('state')->default(1);

            $table->longText('order_reference')->nullable();

            $table->string('email')->nullable();
            $table->double('amount', 20, 2)->default(0);
            $table->json('billing_address')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seb_payments');
    }
}
