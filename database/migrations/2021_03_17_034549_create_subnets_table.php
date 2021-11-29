<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubnetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subnets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->uuid('datacenter_id');
            $table->foreign('datacenter_id')
                ->references('id')
                ->on('datacenters')
                ->onDelete('CASCADE');

            $table->tinyInteger('status')->default(1);
            $table->string('subnet_mask');

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
        Schema::dropIfExists('subnets');
    }
}
