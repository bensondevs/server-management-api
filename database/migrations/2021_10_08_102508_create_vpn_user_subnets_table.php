<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVpnUserSubnetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vpn_user_subnets', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('container_id')
                ->references('id')
                ->on('containers')
                ->onDelete();

            $table->string('subnet');

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
        Schema::dropIfExists('vpn_user_subnets');
    }
}
