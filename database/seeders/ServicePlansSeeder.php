<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\ServicePlan;
use App\Enums\Currency;

class ServicePlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	/**
    	 * Configure free plan
    	 */
    	$freePlan = ServicePlan::factory()->create([
    		'plan_name' => 'Free',
    		'plan_code' => 'free',
    		'description' => 'Lorem ipsum dolor sit amet',
    	]);
    	$freePlan->setDiskSize(100);
    	$freePlan->setDuration(30);
    	$freePlan->setPrice(0, Currency::EUR);
    	$freePlan->setPrice(0, Currency::USD);

    	/**
    	 * Configure standard plan
    	 */
    	$standardPlan = ServicePlan::factory()->create([
    		'plan_name' => 'Standard',
    		'plan_code' => 'standard',
    		'description' => 'Lorem ipsum dolor sit amet',
    	]);
    	$standardPlan->setDiskSize(1000);
    	$standardPlan->setDuration(30);
    	$freePlan->setPrice(10, Currency::EUR);
    	$freePlan->setPrice(12, Currency::USD);

    	/**
    	 * Configure advanced plan
    	 */
    	$advancedPlan = ServicePlan::factory()->create([
    		'plan_name' => 'Advanced',
    		'plan_code' => 'advanced',
    		'description' => 'Lorem ipsum dolor sit amet',
    	]);
    	$advancedPlan->setDiskSize(3000);
    	$advancedPlan->setDuration(30);
    	$freePlan->setPrice(30, Currency::EUR);
    	$freePlan->setPrice(35, Currency::USD);
    }
}
