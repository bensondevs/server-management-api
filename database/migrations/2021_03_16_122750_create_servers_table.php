<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Repositories\ServerRepository;
use App\Repositories\RabbitMQApiRepository;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('datacenter_id');
            $table->foreign('datacenter_id')
                ->references('id')
                ->on('datacenters')
                ->onDelete('CASCADE');
            $table->string('server_name')->unique();
            $table->string('status')->default('active');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE `servers` ADD `ip_binary` VARBINARY(16) UNIQUE AFTER `server_name`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
}
