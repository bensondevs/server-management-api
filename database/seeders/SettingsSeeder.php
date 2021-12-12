<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Setting;
use App\Repositories\SettingRepository;

class SettingsSeeder extends Seeder
{
    /**
     * Setting Repository variable container
     * 
     * @var \App\Models\SettingRepository|null
     */
	private $setting;

    /**
     * Seeder constructor method
     * 
     * @param  \App\Repositories\SettingRepository  $settingRepository
     * @return void
     */
	public function __construct(SettingRepository $settingRepository)
	{
		$this->setting = $settingRepository;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
        	[
        		'key' => 'site_name',
        		'value' => 'Diskay Development',
        	],
        	[
        		'key' => 'site_title',
        		'value' => 'Another Application',
        	],
        	[
        		'key' => 'description',
        		'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean hendrerit scelerisque orci, in placerat justo vulputate non. Donec eget ornare leo. Donec et imperdiet lorem. Nunc sit amet dignissim ipsum. Ut bibendum urna vel purus vehicula faucibus. Integer imperdiet convallis odio, vitae luctus nibh lacinia eget. Nunc posuere mauris id mi mattis suscipit. Morbi at porta metus. Quisque feugiat aliquet facilisis. Quisque ut odio risus. Vestibulum mi justo, imperdiet quis elementum sit amet, ultricies et ipsum. Donec rutrum ipsum vulputate quam sagittis, eget bibendum velit suscipit. Vivamus bibendum augue ligula, nec pretium nisl condimentum vel. Aenean at fringilla risus, ut maximus dui. Suspendisse posuere metus quis nisi fermentum condimentum. Curabitur sagittis rhoncus lacinia. Nunc at diam eu orci egestas dapibus. In rhoncus vulputate condimentum. Cras turpis tortor, pharetra in enim ac, malesuada consequat neque. Mauris dictum sem eu nulla vehicula, et blandit purus imperdiet. Sed tempor faucibus pharetra. Aenean sed fermentum neque, ac mattis diam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nam id arcu in dui suscipit malesuada vestibulum nec risus. Suspendisse magna quam, finibus vel velit et, dapibus interdum ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Etiam non orci vel ex dictum vehicula sit amet in massa. Praesent mattis convallis malesuada. Curabitur volutpat malesuada sagittis. Maecenas in justo id justo semper bibendum id vel leo. Vivamus non magna sed lectus fringilla rhoncus eget at velit. Praesent eget ante sed nunc rhoncus rhoncus iaculis eget risus. Vivamus vel pellentesque velit. Cras dictum dapibus dui vel ultricies. Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque sodales sollicitudin metus quis tempor. Etiam molestie semper ligula, at placerat lectus venenatis eget. Phasellus non elit sed turpis ultrices molestie sed eget leo. Donec ut metus neque. Nulla tincidunt a ex vel ultrices. Proin sed turpis rhoncus massa scelerisque interdum. Sed lacinia ex risus, ac bibendum tellus sagittis eget. Nullam feugiat pharetra nunc. Donec ac tortor venenatis tellus placerat blandit commodo sit amet eros. Nullam a augue at ante vehicula facilisis. Proin pretium mollis gravida. Integer mollis, ante id eleifend iaculis, elit ligula mollis purus, et sodales massa libero eu erat. Nam mattis consectetur egestas. Donec tempus fringilla enim, at commodo massa fermentum sed. Nunc eu justo eget libero euismod ullamcorper. Duis ultrices, lorem id auctor imperdiet, risus mauris hendrerit ex, a sodales sapien neque at ligula. Sed sit amet convallis magna. Sed at bibendum elit. Nulla quis pretium dolor. Duis posuere, arcu nec convallis tincidunt, dolor velit aliquam ante, vel viverra sapien erat id leo. Morbi eget felis ut lectus tincidunt vehicula.'
        	],
            [
                'key' => 'config_rabbitmq_connection',
                'value' => 'env',
            ],
            [
                'key' => 'autosync_rabbitmq',
                'value' => 'on',
            ],
            [
                'key' => 'rabbitmq_host',
                'value' => env('RABBITMQ_HOST', '127.0.0.1'),
            ],
            [
                'key' => 'rabbitmq_port',
                'value' => env('RABBITMQ_PORT', 5672),
            ],
            [
                'key' => 'rabbitmq_vhost',
                'value' => env('RABBITMQ_VHOST', '/'),
            ],
            [
                'key' => 'rabbitmq_user',
                'value' => env('RABBITMQ_USER', 'guest'),
            ],
            [
                'key' => 'rabbitmq_password',
                'value' => env('RABBITMQ_PASSWORD', 'guest'),
            ],
        ];

        foreach ($settings as $setting) {
        	$this->setting->save($setting);
        	$this->setting->setModel(new Setting);
        }
    }
}
