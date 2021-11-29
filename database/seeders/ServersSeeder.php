<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Datacenter;
use App\Models\Server;
use App\Models\Setting;

use App\Repositories\ServerRepository;

use App\Repositories\AmqpRepository;
use App\Repositories\RabbitMQApiRepository;

class ServersSeeder extends Seeder
{
	protected $server;
    protected $amqp;
    // protected $rabbitmqApi;

	public function __construct(
        ServerRepository $serverRepository,
        AmqpRepository $amqpRepository
    )
	{
		$this->server = $serverRepository;
        $this->amqp = $amqpRepository;
	}

	public function generateRandomIp()
	{
		return mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255);
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*for ($iteration = 1; $iteration <= 10; $iteration++) {
            $datacenter = Datacenter::inRandomOrder()->first();

        	$this->server->setModel(new Server(['datacenter_id' => $datacenter->id]));
        	$server = $this->server->save([
        		'server_name' => 'server' . $iteration . '.diskray.com',
        		'ip_address' => $this->generateRandomIp(),
        		'status' => rand(0, 1) ? 'active' : 'inactive',
        	]);

            if ($this->server->status == 'success') {
                $setting = Setting::rabbitMQSettings(true);
                $this->amqp->connect($setting);
                $this->amqp->createQueue($server->complete_server_name);
            }
        }*/

        $datacenter = Datacenter::all()->first();
        $server = $this->server->save([
            'datacenter_id' => $datacenter->id,
            'server_name' => 'server1.diskray.com',
            'ip_address' => '185.111.182.2',
            'status' => 'active',
        ]);

        /*if ($this->server->status == 'success') {
            $this->amqp->connect();
            $this->amqp->createQueue($server->complete_server_name);
        }*/
    }
}
