<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVpnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vpn_users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('container_id')->nullable();
            $table->foreign('container_id')
                ->references('id')
                ->on('containers')
                ->onDelete('SET NULL');

            $table->string('username');

            $table->string('assigned_subnet_ip')->nullable();
            $table->string('vpn_subnet')->default('10.0.0/8');
            
            $table->text('config_content')->nullable();

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
        Schema::dropIfExists('vpn_users');
    }
}
