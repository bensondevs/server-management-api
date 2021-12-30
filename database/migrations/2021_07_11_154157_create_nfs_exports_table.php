<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNfsExportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nfs_exports', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('container_id');
            $table->foreign('container_id')
                ->references('id')
                ->on('containers')
                ->onDelete('CASCADE');

            $table->uuid('nfs_folder_id');
            $table->foreign('nfs_folder_id')
                ->references('id')
                ->on('nfs_folders')
                ->onDelete('CASCADE');

            $table->string('permissions');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE `nfs_exports` ADD `ip_binary` VARBINARY(16) AFTER `id`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::statement('ALTER TABLE `nfs_exports` DROP COLUMN `ip_binary`');

        Schema::dropIfExists('nfs_exports');
    }
}
