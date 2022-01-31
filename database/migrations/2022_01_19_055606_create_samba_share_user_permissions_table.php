<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSambaShareUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('samba_share_user_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('samba_share_id');
            $table->foreign('samba_share_id')
                ->references('id')
                ->on('samba_shares')
                ->onDelete('CASCADE');

            $table->uuid('samba_share_user_id');
            $table->foreign('samba_share_user_id')
                ->references('id')
                ->on('samba_share_users')
                ->onDelete('CASCADE');

            $table->boolean('read')->default(true);
            $table->boolean('write')->default(false);

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
        Schema::dropIfExists('samba_share_permissions');
    }
}
