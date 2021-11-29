<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('order_number');

            $table->tinyInteger('status')->default(1);

            $table->uuid('customer_id');
            $table->foreign('customer_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->double('amount')->default(0);
            $table->float('vat_size_percentage')->default(0);
            $table->double('total_amount', 20, 2)->default(0);

            /* Save pre-created container */
            $table->json('meta_container')->nullable();

            $table->datetime('expired_at')->nullable();

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
        Schema::dropIfExists('orders');
    }
}
