<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSebPaymentApiResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seb_payment_api_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('seb_payment_id');
            $table->foreign('seb_payment_id')
                ->references('id')
                ->on('seb_payments')
                ->onDelete('CASCADE');

            $table->string('requester_ip')->nullable();
            $table->json('response');

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
        Schema::dropIfExists('seb_payment_api_responses');
    }
}
