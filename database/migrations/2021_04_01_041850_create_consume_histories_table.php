<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consume_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('command_history_id');
            $table->foreign('command_history_id')
                ->references('id')
                ->on('command_histories')
                ->onDelete('CASCADE');

            $table->string('consumed_queue_name');

            $table->text('output');

            $table->string('consumer_ip_binary');
            $table->datetime('consumed_at');

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
        Schema::dropIfExists('consume_histories');
    }
}
