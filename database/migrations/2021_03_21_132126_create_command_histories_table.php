<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('command_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('executor_id')->nullable();
            $table->foreign('executor_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->string('queue_name');
            $table->text('content');

            $table->string('executed_from');
            $table->datetime('executed_at');

            $table->json('execution_errors_json')->nullable();

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
        Schema::dropIfExists('command_histories');
    }
}
