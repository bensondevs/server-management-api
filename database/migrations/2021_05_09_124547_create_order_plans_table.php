<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('order_id');
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('CASCADE');

            $table->uuid('service_plan_id');
            $table->foreign('service_plan_id')
                ->references('id')
                ->on('service_plans')
                ->onDelete('CASCADE');

            $table->integer('quantity')->default(1);
            $table->text('note')->nullable();

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
        Schema::dropIfExists('order_plans');
    }
}
