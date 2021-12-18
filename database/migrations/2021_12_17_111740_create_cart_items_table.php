<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('cart_id');
            $table->foreign('cart_id')
                ->references('id')
                ->on('carts')
                ->onDelete('CASCADE');

            $table->nullableUuidMorphs('cartable');
            $table->integer('quantity');
            $table->double('sub_total', 4, 2);
            $table->double('discount', 8, 2);

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
        Schema::dropIfExists('cart_items');
    }
}
