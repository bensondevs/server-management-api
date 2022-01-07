<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\Container\{
    ContainerStatus as Status,
    ContainerOnServerStatus as StatusOnServer
};
use App\Enums\Container\Vpn\{
    ContainerVpnStatus as VpnStatus,
    ContainerVpnEnability as VpnEnability
};
use App\Enums\Container\Nfs\{
    ContainerNfsStatus as NfsStatus,
    ContainerNfsEnability as NfsEnability
};
use App\Enums\Container\Nginx\{
    ContainerNginxStatus as NginxStatus,
    ContainerNginxEnability as NginxEnability
};
use App\Enums\Container\Samba\{
    ContainerSambaBindPublicIp as SambaBindPublicIp,
    ContainerSambaNmbdEnability as SambaNmbdEnability,
    ContainerSambaSmbdEnability as SambaSmbdEnability,
    ContainerSambaNmbdStatus as SambaNmbdStatus,
    ContainerSambaSmbdStatus as SambaSmbdStatus
};

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

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');

            $table->uuid('precreated_container_id')->nullable();
            $table->foreign('precreated_container_id')
                ->references('id')
                ->on('precreated_containers')
                ->onDelete('SET NULL');

            $table->uuid('region_id');
            $table->foreign('region_id')
                ->references('id')
                ->on('regions')
                ->onDelete('CASCADE');

            $table->uuid('datacenter_id');
            $table->foreign('datacenter_id')
                ->references('id')
                ->on('datacenters')
                ->onDelete('CASCADE');

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
            $table->tinyInteger('status')->default(Status::Inactive);
            $table->tinyInteger('status_on_server')->default(StatusOnServer::Uncreated);

            // Services Timestamps
            $table->timestamp('created_on_server_at')->nullable();
            $table->timestamp('system_installed_at')->nullable();

            // VPN Service
            $table->tinyInteger('vpn_status')->default(VpnStatus::Unknown);
            $table->json('vpn_pid_numbers')->nullable();
            $table->tinyInteger('vpn_enability')->default(VpnStatus::Unknown);

            // Samba Service
            $table->tinyInteger('samba_smbd_status')->default(SambaSmbdStatus::Unknown);
            $table->tinyInteger('samba_nmbd_status')->default(SambaNmbdStatus::Unknown);
            $table->json('samba_pid_numbers')->nullable();
            $table->tinyInteger('samba_smbd_enability')->default(SambaSmbdEnability::Unknown);
            $table->tinyInteger('samba_nmbd_enability')->default(SambaNmbdEnability::Unknown);
            $table->tinyInteger('samba_bind_to_public_ip')->default(SambaBindPublicIp::Unbinded);

            // NFS Service
            $table->tinyInteger('nfs_status')->default(NfsStatus::Unknown);
            $table->json('nfs_pid_numbers')->nullable();
            $table->tinyInteger('nfs_enability')->default(NfsEnability::Unknown);

            // NGINX Service
            $table->tinyInteger('nginx_status')->default(NginxStatus::Unknown);
            $table->json('nginx_pid_numbers')->nullable();
            $table->tinyInteger('nginx_enability')->default(NginxEnability::Unknown);

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
