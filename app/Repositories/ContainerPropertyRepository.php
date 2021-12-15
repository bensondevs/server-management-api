<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ 
	Container, 
	ContainerProperty as Property 
};
use App\Enums\Container\ContainerPropertyType;

class ContainerPropertyRepository extends BaseRepository
{
	/**
	 * Current container model container
	 * 
	 * @var \App\Models\Container|null
	 */
	private $container;

	/**
	 * Current container property model container
	 * 
	 * @var \App\Models\ContainerProperty
	 */
	private $property;

	/**
	 * Set container of the property
	 * 
	 * @param  \App\Models\Container  $container
	 * @return void
	 */
	public function setContainer(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Get container of the property
	 * 
	 * @return  \App\Models\Container
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * Set container property
	 * 
	 * @param  \App\Models\ContainerProperty
	 */
	public function setContainerProperty(Property $property)
	{
		$this->property = $property;
	}

	/**
	 * Get container property
	 * 
	 * @return  \App\Models\ContainerProperty
	 */
	public function getContainerProperty()
	{
		return $this->property;
	}

	/**
	 * Syncronise the container property to server
	 * 
	 * @return \App\Models\ContainerProperty 
	 */
	public function syncronise()
	{
		//
	}
}
