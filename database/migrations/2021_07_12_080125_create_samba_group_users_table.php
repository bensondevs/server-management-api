<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSambaGroupUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('samba_group_users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('container_id');
            $table->foreign('container_id')
                ->references('id')
                ->on('containers')
                ->onDelete('CASCADE');

            $table->uuid('samba_group_id');
            $table->foreign('samba_group_id')
                ->references('id')
                ->on('samba_groups')
                ->onDelete('CASCADE');

            $table->uuid('samba_user_id');
            $table->foreign('samba_user_id')
                ->references('id')
                ->on('samba_users')
                ->onDelete('CASCADE');

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
        Schema::dropIfExists('samba_group_users');
    }
}
