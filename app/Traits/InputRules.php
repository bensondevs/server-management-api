<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait InputRules 
{
    private $model;
	private $rules;

	public function setModel(Model $model)
	{
		$this->model = $model;
	}

	public function setRules(array $rules)
	{
		$this->rules = $rules;
	}

	public function addRule(string $inputKey, array $rule)
	{
		$this->rules[$inputKey] = $rule;
	}

	public function returnRules()
	{
		$rules = $this->rules;

		/*
			UPDATE REQUEST ONLY
		*/
		if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
			/*
				Loop and check all the requests.
				if the old request is the same as new request, 
				let it just pass anyway
			*/
			foreach ($rules as $key => $rule) {
				if ($this->model)
					if ($this->input($key) === $this->model->{$key})
						unset($rules[$key]);
			}
		}

		return $rules;
	}

    public function onlyInRules()
    {
    	$rules = $this->rules();
    	$ruleKeys = array_keys($rules);
    	return $this->only($ruleKeys);
    }
}