<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Repositories\ServicePlanRepository;

use App\Models\ServicePlan;

class ServicePlansSeeder extends Seeder
{
	protected $plan;

	public function __construct(
        ServicePlanRepository $servicePlanRepository
    )
	{
		$this->plan = $servicePlanRepository;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$this->plan->save([
    		'plan_code' => 'free',
			'plan_name' => 'free',
	        'time_quantity' => 1,
	        'time_unit' => 2,
	        'description' => 'Random created plan.',
    	]);
    	$this->plan->setModel(new ServicePlan);

    	$this->plan->save([
    		'plan_code' => 'standard',
			'plan_name' => 'standard',
	        'time_quantity' => 1,
	        'time_unit' => 2,
	        'description' => 'Random created plan.',
    	]);
    	$this->plan->setModel(new ServicePlan);

    	$this->plan->save([
    		'plan_code' => 'advanced',
			'plan_name' => 'advanced',
	        'time_quantity' => 1,
	        'time_unit' => 2,
	        'description' => 'Random created plan.',
    	]);
    	$this->plan->setModel(new ServicePlan);

    	$this->plan->save([
    		'plan_code' => 'custom',
			'plan_name' => 'custom',
	        'time_quantity' => 1,
	        'time_unit' => 2,
	        'description' => 'Random created plan.',
    	]);
    	$this->plan->setModel(new ServicePlan);
    }
}
