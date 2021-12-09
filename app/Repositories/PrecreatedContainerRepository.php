<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\PrecreatedContainer;
use App\Enums\PrecreatedContainer\PrecreatedContainerStatus as Status;

class PrecreatedContainerRepository extends BaseRepository
{
	/**
	 * Repository class constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new PrecreatedContainer);
	}

	/**
	 * Save pre-created container and wait for order payment
	 * 
	 * @return void
	 */
	public function prepare()
	{
		//
	}

	/**
	 * Process pre-created container.
	 * If the supplied data like desired subnet is available
	 * This function will generate container using data stored in it.
	 * 
	 * Else, the pre-created container status will be set to waiting.
	 * This is also called waiting list container.
	 * 
	 * @return \App\Models\PrecreatedContainer
	 */
	public function process()
	{
		//
	}

	/**
	 * Reprocess the pre-created container 
	 * that is in waiting status
	 * 
	 * @return \App\Models\PrecreatedContainer
	 */
	public function reprocess()
	{
		//
	}
}
