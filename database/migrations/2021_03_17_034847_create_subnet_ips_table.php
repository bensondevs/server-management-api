<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubnetIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subnet_ips', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('subnet_id');
            $table->foreign('subnet_id')
                ->references('id')
                ->on('subnets')
                ->onDelete('CASCADE');

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->tinyInteger('status')->default(1);

            $table->text('comment');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE `subnet_ips` ADD `ip_binary` VARBINARY(16) AFTER `id`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `subnet_ips` DROP COLUMN `ip_binary`');
        Schema::dropIfExists('subnet_ips');
    }
}
