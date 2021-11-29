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

            $table->longText('payment_reference')->nullable();
            $table->char('payment_state');
            $table->double('amount', 20, 2)->default(0);

            $table->json('billing_address');
            $table->json('api_response')->nullable();

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
