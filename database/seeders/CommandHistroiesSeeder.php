<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

use App\Repositories\CommandHistoryRepository;

class CommandHistroiesSeeder extends Seeder
{
	protected $commandHistory;

	public function __construct(
		CommandHistoryRepository $commandHistoryRepository
	)
	{
		$this->commandHistory = $commandHistoryRepository;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	/*$executor = User::all()->first();

        $this->commandHistory->setExecutor($executor);
        $this->commandHistory->executeCommand('echo "This is test command..."');
        $this->commandHistory->clearVariables();

        $this->commandHistory->setExecutor($executor);
        $this->commandHistory->executeCommand('echo "This is another test command..."');
        $this->commandHistory->clearVariables();*/
    }
}
