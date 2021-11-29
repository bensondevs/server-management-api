<?php

namespace App\Traits\Container;

use App\Models\NginxLocation;

use App\Enums\Container\Nginx\{
	ContainerNginxStatus as Status, 
	ContainerNginxEnability as Enability
};

trait NginxTrait
{
	/**
     * Get existing NGINX Locations
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nginxLocations()
    {
        return $this->hasMany(NginxLocation::class);
    }

	/**
	 * Get current NGINX status
	 * 
	 * @return int
	 */
	public function getCurrentNginxStatusAttribute()
	{
		if (! $nginxStatus = $this->attributes['nginx_status']) {
			$nginxStatus = Status::Requesting;
		}

		return $nginxStatus;
	}

	/**
	 * Set NGINX Status
	 * 
	 * @param string  $nginxStatus
	 * @return void
	 */
	public function setNginxStatusAttribute(string $nginxStatus)
	{
		$nginxStatus = preg_replace('~[\r\n]~', '', $nginxStatus);
		$nginxStatus = str_replace('\n', '', $nginxStatus);
		$enum = (Status::fromKey(ucfirst($sambaStatus)));
        $status = isset($enum->value) ? $enum->value : Status::Unknown;
        $this->attributes['nginx_status'] = $status;
	}

	/**
	 * Get Current NGINX PID Numbers
	 * 
	 * @return int
	 */
	public function getCurrentNginxPidNumbersAttribute()
	{
		$nginxPidNumbers = $this->attributes['nginx_pid_numbers'];
		if (is_array($nginxPidNumbers)) {
			return $nginxPidNumbers;
		}

		if (is_string($nginxPidNumbers)) {
			if (! $nginxPidNumbersArray = json_decode($nginxPidNumbers, true)) {
				return [$nginxPidNumbers];
			}

			return $nginxPidNumbersArray;
		}

		return $nginxPidNumbers;
	}

	/**
	 * Set NGINX PID Numbers
	 * 
	 * @param array  $nginxPidNumbers
	 * @return void
	 */
	public function setNginxPidNumbersAttribute(array $nginxPidNumbers)
	{
		if (! $nginxPidNumbers) {
			return;
		}

		if (is_string($nginxPidNumbers)) {
			$nginxPidNumbers = preg_replace('~[\r\n]~', '', $nginxPidNumbers);
			$nginxPidNumbers = explode(' ', $nginxPidNumbers);
		}

		$nginxPidNumbers = json_encode($nginxPidNumbers);
		$this->attributes['nginx_pid_numbers'] = $nginxPidNumbers;
	}

	/**
	 * Get current NGINX Enability
	 * 
	 * @return int
	 */
	public function getCurrentNginxStartOnBootStatusAttribute()
	{
		return $this->attributes['nginx_enability'];
	}

	/**
	 * Set NGINX Enability
	 * 
	 * @param string  $enability
	 * @return void
	 */
	public function setNginxEnabilityAttribute(string $enability)
	{
		$statusKey = ucfirst($enability);
		$enability = (Enability::fromKey($statusKey))->value;
        $this->attributes['nginx_enability'] = $enability;
	}
}