<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('containers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('subscription_id');
            $table->foreign('subscription_id')
                ->references('id')
                ->on('subscriptions')
                ->onDelete('CASCADE');

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');

            $table->uuid('server_id');
            $table->foreign('server_id')
                ->references('id')
                ->on('servers')
                ->onDelete('CASCADE');

            $table->uuid('subnet_id');
            $table->foreign('subnet_id')
                ->references('id')
                ->on('subnets')
                ->onDelete('CASCADE');

            $table->uuid('subnet_ip_id');
            $table->foreign('subnet_ip_id')
                ->references('id')
                ->on('subnet_ips')
                ->onDelete('CASCADE');
            
            // Container Informations
            $table->string('hostname');
            $table->integer('total_amount')->default(0);
            $table->string('client_email')->nullable();

            // Statuses
            $table->tinyInteger('current')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('status_on_server')->default(0);

            // Spesifications
            $table->integer('disk_size')->default(50);
            $table->integer('disk_array')->default(1);
            $table->integer('breakpoints')->default(1);

            // Order and Expirations
            $table->date('order_date')->nullable();
            $table->date('activation_date')->nullable();
            $table->date('expiration_date')->nullable();

            // Services Timestamps
            $table->timestamp('created_on_server_at')->nullable();
            $table->timestamp('system_installed_at')->nullable();

            // VPN Service
            $table->tinyInteger('vpn_status')->default(2);
            $table->json('vpn_pid_numbers')->nullable();
            $table->tinyInteger('vpn_enability')->default(2);

            // Samba Service
            $table->tinyInteger('samba_smbd_status')->default(2);
            $table->tinyInteger('samba_nmbd_status')->default(2);
            $table->json('samba_pid_numbers')->nullable();
            $table->tinyInteger('samba_smbd_enability')->default(2);
            $table->tinyInteger('samba_nmbd_enability')->default(2);

            // NFS Service
            $table->tinyInteger('nfs_status')->default(2);
            $table->json('nfs_pid_numbers')->nullable();
            $table->tinyInteger('nfs_enability')->default(2);

            // NGINX Service
            $table->tinyInteger('nginx_status')->default(2);
            $table->json('nginx_pid_numbers')->nullable();
            $table->tinyInteger('nginx_enability')->default(2);

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
        Schema::dropIfExists('containers');
    }
}
