<?php

namespace App\Traits;

trait RequestHasRelations 
{
    /**
     * Prepare input to configure the relations
     * 
     * @return array
     */
    protected function prepareRelationInputs()
    {
        if (! isset($this->relationNames)) {
            return [];
        }
        
        foreach ($this->relationNames as $requestKey => $defaultValue) {
            if ($this->has($requestKey)) {
                $inputValue = strtobool($this->input($requestKey));
                $this->merge([$requestKey => $inputValue]);
            }
        }
    }

    /**
     * Get relationship by configurations
     * 
     * @return array
     */
    public function getRelations()
    {
        if (! isset($this->relationNames)) {
            return [];
        }

        $relations = [];
        foreach ($this->relationNames as $name => $defaultValue) {
            $relationName = str_replace('with_', '', $name);
            $relationName = str_camel_case($relationName);

            /*
                Get request key name, if not set then get the default value 
            */
            if ($this->input($name, $defaultValue)) {
                $relations[] = $relationName;
            }
        }

        return $relations;
    }

    /**
     * Shorter version of "getRelations"
     * 
     * @return array
     */
    public function relations()
    {
        return $this->getRelations();
    }

    /**
     * Get all relation that has types of counts
     * 
     * @return array
     */
    public function getRelationCounts()
    {
        if (isset($this->relationCountNames)) {
            return [];
        }

        $relationCounts = [];
        foreach ($this->relationCountNames as $name => $defaultValue) {
            $relationCountName = str_replace('with_', '', $name);
            $relationCountName = str_replace('_count');

            if ($this->input($name, $defaultValue)) {
                $relationCounts[] = $relationCountName;
            }
        }

        return $relationCounts;
    }

    /**
     * Shorter version of "getRelationCounts()"
     * 
     * @return array
     */
    public function relationCounts()
    {
        return $this->getRelationCounts();
    }
}