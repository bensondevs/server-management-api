<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSambaSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('samba_shares', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('container_id');
            $table->foreign('container_id')
                ->references('id')
                ->on('containers')
                ->onDelete('CASCADE');

            $table->string('share_name')->nullable();
            $table->text('share_config_content')->nullable();
            $table->char('permissions')->default('r');

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
        Schema::dropIfExists('samba_shares');
    }
}
