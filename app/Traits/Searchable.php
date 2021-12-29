<?php

namespace App\Traits;

trait Searchable 
{
    /**
     * Get serachable columns list
     * 
     * @return array|null
     */
    public function getSearchable()
    {
        return $this->searchable;
    }
}