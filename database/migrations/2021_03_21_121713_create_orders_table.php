<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\Currency;
use App\Enums\Order\{
    OrderStatus as Status,
    OrderType as Type
};

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

            $table->tinyInteger('type')->default(Type::New);
            $table->tinyInteger('status')->default(Status::Unpaid);

            $table->uuid('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->tinyInteger('currency')->default(Currency::EUR);
            $table->double('total')->default(0);
            $table->float('vat_size_percentage')->default(0);
            $table->double('grand_total', 20, 2)->default(0);

            $table->timestamps();
            $table->datetime('paid_at')->nullable();
            $table->datetime('expired_at')->nullable();
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
