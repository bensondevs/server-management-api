<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVpnUserConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vpn_user_configs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('container_id')->nullable();
            $table->foreign('container_id')
                ->references('id')
                ->on('containers')
                ->onDelete('SET NULL');

            $table->string('username');
            $table->text('config_content');

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
        Schema::dropIfExists('vpn_user_configs');
    }
}
