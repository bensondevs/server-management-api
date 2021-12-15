<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{ Container, Subscription, User, Server, Subnet, SubnetIp, ServicePlan };
use App\Enums\Container\{
    ContainerStatus as Status,
    ContainerOnServerStatus as OnServerStatus,

    Vpn\ContainerVpnStatus as VpnStatus,
    Vpn\ContainerVpnEnability as VpnEnability,

    Samba\ContainerSambaNmbdStatus as SambaNmbdStatus,
    Samba\ContainerSambaSmbdStatus as SambaSmbdStatus,
    Samba\ContainerSambaSmbdEnability as SambaSmbdEnability,
    Samba\ContainerSambaNmbdEnability as SambaNmbdEnability,

    Nginx\ContainerNginxStatus as NginxStatus,
    Nginx\ContainerNginxEnability as NginxEnability,

    Nfs\ContainerNfsStatus as NfsStatus,
    Nfs\ContainerNfsEnability as NfsEnability,
};
use App\Enums\ContainerProperty\ContainerPropertyType as PropType;

class ContainerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Container::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Container $container) {
            /**
             * Assign user if no user attached
             */
            if (! $container->user_id) {
                $user = User::factory()->create();
                $container->user_id = $user->id;
            }

            /**
             * Assign server if no server attached
             */
            if (! $container->server_id) {
                $server = Server::first();
                $container->server_id = $server->id;
            }

            /**
             * Assign subnet if no subnet attached
             */
            if (! $container->subnet_id) {
                $subnet = Subnet::available()->first();
                $container->subnet_id = $subnet->id;
            }

            /**
             * Assign subnet ip id of no subnet ip id attached
             */
            if (! $container->subnet_ip_id) {
                $subnet = Subnet::findOrFail($container->subnet_id);
                $subnetIp = SubnetIp::free()
                    ->where('subnet_id', $subnet->id)
                    ->first();
                $container->subnet_ip_id = $subnetIp->id;
            }
        })->afterCreating(function (Container $container) {
            $user = $container->user;

            /**
             * Assign the properties
             */
            $faker = $this->faker;
            $container->setProperty(PropType::DiskSize, $faker->randomNumber(2, true));
            $container->setProperty(PropType::DiskArray, $faker->randomNumber(1, true));
            $container->setProperty(PropType::Breakpoints, $faker->randomNumber(1, true));

            /**
             * Set subscription to container
             */
            $subscription = Subscription::factory()
                ->for($user)
                ->subscriber($container)
                ->subscribeable(ServicePlan::first())
                ->create();
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        return [
            'hostname' => 'example.container.test',
            'total_amount' => $faker->randomNumber(3, false),
            'client_email' => $faker->safeEmail(),

            'current' => false,
            'status' => Status::Inactive,
            'status_on_server' => OnServerStatus::Uncreated,

            'disk_size' => $faker->randomNumber(3, false),
            'disk_array' => $faker->randomNumber(3, false),
            'breakpoints' => $faker->randomNumber(2, false),

            'created_on_server_at' => null,
            'system_installed_at' => null,

            'vpn_status' => VpnStatus::Unknown,
            'vpn_enability' => VpnEnability::Unknown,

            'samba_smbd_status' => SambaSmbdStatus::Unknown,
            'samba_nmbd_status' => SambaNmbdStatus::Unknown,
            'samba_pid_numbers' => json_encode([]),
            'samba_smbd_enability' => SambaSmbdEnability::Unknown,
            'samba_nmbd_enability' => SambaSmbdEnability::Unknown,

            'nfs_status' => NfsStatus::Unknown,
            'nfs_enability' => NfsEnability::Unknown,

            'nginx_status' => NginxStatus::Unknown,
            'nginx_enability' => NginxEnability::Unknown,
        ];
    }
}
