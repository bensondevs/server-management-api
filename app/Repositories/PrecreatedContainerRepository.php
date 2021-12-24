<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ 
	Region,
	PrecreatedContainer, 
	ServicePlan, 
	Container, 
	Subscription 
};
use App\Enums\PrecreatedContainer\{
	WaitingReason as Reason,
	PrecreatedContainerStatus as Status
};

class PrecreatedContainerRepository extends BaseRepository
{
	/**
	 * Created container as result of process
	 * 
	 * @var \App\Models\Container|null
	 */
	private $container;

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
	 * Set container after the process
	 * 
	 * @param  \App\Models\Container
	 * @return void
	 */
	private function setContainer(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Get container that has been set to the class variable
	 * 
	 * @return \App\Models\Container|null
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * Save pre-created container and wait for order payment
	 * 
	 * @param  \App\Models\Order  $order
	 * @param  array  $metaContainer
	 * @return void
	 */
	public function prepare(Order $order, array $metaContainer)
	{
		try {
			// Prepare basic information of pre-created container
			$preContainer = new PrecreatedContainer();
			$preContainer->order_id = $order->id;
			$preContainer->user_id = $order->user_id;

			// Set container information using meta
			$preContainer->meta_container = $metaContainer;

			// Set container properties by 
			// applying service plan and addons from order items
			foreach ($order->items as $item) {			
				$preContainer->applyService($item);
			}

			// Set status as prepared
			$preContainer->setPrepared();
			
			$this->setModel($preContainer);

			$this->setSuccess('Successfully prepare pre-created container.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to prepare pre-created container.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Add service plan to pre-created container
	 * 
	 * @param  \App\Models\ServicePlan  $plan
	 * @return \App\Models\PrecreatedContainer
	 */
	public function addServicePlan(ServicePlan $plan)
	{
		try {
			$preContainer = $this->getModel();
			$preContainer->applyServicePlan($plan);
			$preContainer->save();

			$this->setModel($preContainer);

			$this->setSuccess('Successfully add service plan to pre-created container.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to add service plan to pre-created container', $error);
		}

		return $this->getModel();
	}

	/**
	 * Add service addon to pre-created container
	 * 
	 * @param  \App\Models\ServiceAddon  $addon
	 * @return \App\Models\PrecreatedContainer
	 */
	public function addServiceAddon(ServiceAddon $addon)
	{
		try {
			$preContainer = $this->getModel();
			$preContainer->applyServiceAddon($addon);
			$preContainer->save();

			$this->setModel($preContainer);

			$this->setSuccess('Successfully add service addon to pre-created container.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to add service addon to pre-created container.', $error);
		}

		return $this->getModel();
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
		$preContainer = $this->getModel();

		/*
	    |--------------------------------------------------------------------------
	    | Create container from meta_container data
	    |--------------------------------------------------------------------------
	    */
		$metaContainer = $preContainer['meta_container'];

		/**
		 * See selected region of the container
		 */
		$region = Region::find($metaContainer['region_id']);
		
		/**
		 * Find best datacenter under selected region
		 */
		if (! $datacenter = $region->selectBestDatacenter()) {
			return $this->sendToQueue(Reason::NoDatacenterAvailable);
		}
		$metaContainer['datacenter_id'] = $datacenter->id;

		/**
		 * Find best server under selected datacenter
		 */
		if (! $server = $datacenter->selectBestServer()) {
			return $this->sendToQueue(Reason::NoServerAvailable);
		}
		$metaContainer['server_id'] = $server->id;

		/**
		 * Find subnet and check availability
		 */
		if (! $subnet = $datacenter->selectBestSubnet()) {
			return $this->sendToQueue(Reason::NoSubnetAvailable);
		}
		$metaContainer['subnet_id'] = $subnet->id;

		/**
		 * Search available subnet ip of the selected subnet
		 * 
		 * If found, set it to the "subnet_ip_id" value
		 */
		if (! $ip = $subnet->selectFreeIp()) {
			return $this->sendToQueue(Reason::NoSubnetIpAvailable);
		}
		$metaContainer['subnet_ip_id'] = $ip->id;

		/**
		 * Create the container using the prepared data
		 */
		try {
			$container = new Container($metaContainer);
			$container->user_id = $preContainer->user_id;
			$container->precreated_container_id = $preContainer->id;
			$container->save();
			$this->setContainer($container);

			$this->setSuccess('Successfully create container from meta container.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$message = 'Failed to create container from meta container.';
			$message .= 'Sending meta container to waiting list';
			$this->setError($message, $error);

			return $this->sendToQueue(Reason::InternalServerError);
		}

		/*
	    |--------------------------------------------------------------------------
	    | Set container attributes from meta_container_properties
	    |--------------------------------------------------------------------------
	    */
		foreach ($preContainer['meta_container_properties'] as $property) {
			$type = $property['property_type'];
			$value = $property['property_value'];
			$container->setProperty($type, $value);
		}

		/*
	    |--------------------------------------------------------------------------
	    | Set container attributes from meta_container_additional_properties
	    |--------------------------------------------------------------------------
	    */
	    /*$additionalProps = $preContainer['meta_container_additional_properties'];
	    foreach ($additionalProps as $additionalProp) {
			$type = $additionalProp['property_type'];
			$value = $additionalProp['property_value'];
			$container->setProperty($type, $value);
		}*/

		/*
	    |--------------------------------------------------------------------------
	    | Create subscription for connecting container to the subscription time
	    |--------------------------------------------------------------------------
	    */
	    try {
		    $order = $preContainer->order;

		    $rawSubscriptions = [];
		    foreach ($order->items as $item) {
		    	$rawSub = [
		    		'id' => generateUuid(),
		    		'user_id' => $order->user_id,
		    		'start' => now(),
		    		'created_at' => now(),
		    		'updated_at' => now(),
		    	];

		    	switch ($item->itemable_type) {

		    		/**
		    		 * If item is service plan, set container as subscriber.
		    		 */
		    		case ServicePlan::class:
		    			$itemable = $item->itemable;

		    			$rawSub['subscribeable_type'] = ServicePlan::class;
		    			$rawSub['subscribeable_id'] = $itemable->id;
		    			$rawSub['subscriber_type'] = Container::class;
		    			$rawSub['subscriber_id'] = $container->id;
		    			
		    			$rawSub['end'] = now()->addDays($itemable->duration_days);
		    			break;

		    		/**
		    		 * If item is service addon, create container additional property
		    		 * and set it as the subscriber.
		    		 */
		    		case ServiceAddon::class:
		    			$rawSub['subscribeable_type'] = ServiceAddon::class;
		    			$rawSub['subscribeable_id'] = $item->itemable_id;

		    			$addon = $item->itemable;
		    			$addPropType = $addon->property_type;
		    			$additionalProp = $container->setAdditionalProp($addPropType);

		    			$rawSub['subscriber_type'] = ContainerAdditionalProperty::class;
		    			$rawSub['subscriber_id'] = $additionalProp->id;

		    			$rawSub['end'] = now()->addDays($addon->duration_days);
		    			break;
		    		
		    		/**
		    		 * If none match, skip this order item
		    		 */
		    		default:
		    			continue 2;
		    			break;
		    	}
		    	array_push($rawSubscriptions, $rawSub);
		    }
		    Subscription::insert($rawSubscriptions);

		    /*
		    |--------------------------------------------------------------------------
		    | Set pre-created container status as "Created"
		    |--------------------------------------------------------------------------
		    */
		    $preContainer->setCreated();

		    $this->setModel($preContainer);
	    	
		    $this->setSuccess('Successfully create subscription.');
	    } catch (QueryException $qe) {
	    	$error = $qe->getMessage();
	    	$this->setError('Failed to create subscription.', $error);
	    }
	}

	/**
	 * Send pre-created container to waiting list
	 * 
	 * @param  int     $reason
	 * @param  string  $description
	 * @return \App\Models\PrecreatedContainer
	 */
	public function sendToQueue(int $reason = 100, string $description = '')
	{
		try {
			$preContainer = $this->getModel();
			$preContainer->waiting_reason = $reason;
			if ($reason == Reason::OtherReason) {
				$preContainer->other_reason_decription = $description;
			}
			$preContainer->setWaiting();

			$this->setModel($preContainer);

			$this->setSuccess('Successfully send pre-created container to queue.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to send to queue.', $error);
		}

		return $this->getModel();
	}
}