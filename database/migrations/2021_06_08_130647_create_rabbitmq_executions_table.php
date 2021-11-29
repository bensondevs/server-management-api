<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRabbitmqExecutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rabbitmq_executions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->json('data');
            $table->string('server_target');
            $table->datetime('sent_at');

            $table->datetime('consumed_at')->nullable();
            $table->string('consume_status')->default('unconsumed');

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
        Schema::dropIfExists('rabbitmq_executions');
    }
}
