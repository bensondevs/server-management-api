<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNfsFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nfs_folders', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('container_id');
            $table->foreign('container_id')
                ->references('id')
                ->on('containers')
                ->onDelete('CASCADE');

            $table->string('folder_name');

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
        Schema::dropIfExists('nfs_folders');
    }
}
