<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\PrecreatedContainer\PrecreatedContainerStatus as Status;

class CreatePrecreatedContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precreated_containers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->uuid('order_id');
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('CASCADE');

            $table->tinyInteger('status')->default(Status::Prepared);
            $table->json('precreated_container_data')->nullable();

            $table->timestamps();
            $table->timestamp('prepared_at')->nullable();
            $table->timestamp('container_created_at')->nullable();

            $table->tinyInteger('waiting_reason')->nullable();
            $table->string('other_reason_description')->nullable();
            $table->timestamp('waiting_since')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('precreated_containers');
    }
}
