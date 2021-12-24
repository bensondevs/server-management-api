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

            $table->nullableUuidMorphs('cart_itemable');
            $table->integer('quantity')->default(1);
            $table->double('sub_total', 4, 2)->default(0);
            $table->double('discount', 8, 2)->default(0);

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
