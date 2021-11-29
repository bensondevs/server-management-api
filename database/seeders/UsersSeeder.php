<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

use App\Repositories\UserRepository;

class UsersSeeder extends Seeder
{
	protected $user;

	public function __construct(UserRepository $userRepository)
	{
		$this->user = $userRepository;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$this->user->setModel(new User);
        $this->user->save([
        	'account_type' => 'personal',

        	'first_name' => 'Admin',
			'middle_name' => 'of',
			'last_name' => 'Diskray',

			'country' => 'Lithuania',
			'address' => 'Diskray Road',
			'vat_number' => 'LT02831937812',

			'username' => 'admin',
			'email' => 'admin@diskray.lt',
			'unhashed_password' => 'uNVfM902sW',

			'company_name' => 'Benson Devs',

			'newsletter' => true,

			'notes' => 'Cool',
        ]);
        $this->user->verifyEmail();

        $this->user->setModel(new User);
        $this->user->save([
        	'account_type' => 'personal',

        	'first_name' => 'Diskray',
			'last_name' => 'User',

			'country' => 'Lithuania',
			'address' => 'Diskray Road',
			'vat_number' => 'ID98831267312',

			'username' => 'user',
			'email' => 'user@diskray.lt',
			'unhashed_password' => 'pd3ZdcQCBg',

			'company_name' => 'Benson Devs',

			'newsletter' => true,

			'notes' => 'Cool',
        ]);
        $this->user->verifyEmail();
    }
}
