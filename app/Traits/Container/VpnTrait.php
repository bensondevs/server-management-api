<?php

namespace App\Traits\Container;

use App\Enums\Container\Vpn\{
	ContainerVpnStatus as Status, 
	ContainerVpnEnability as Enability
};

use App\Models\{ VpnUser, VpnSubnet };

trait VpnTrait
{
	/**
	 * Get Container Existing VPN Users
	 */
	public function vpnUsers()
    {
        return $this->hasMany(VpnUser::class);
    }

    /**
     * Get Container Existing
     */
    public function vpnSubnets()
    {
    	return $this->hasMany(VpnSubnet::class);
    }

	/**
	 * Get current vpn status
	 * 
	 * @return int
	 */
	public function getCurrentVpnStatusAttribute()
	{
		if (! $vpnStatus = $this->attributes['vpn_status']) {
			$vpnStatus = Status::Requesting;
		}

		return $vpnStatus;
	}

	/**
	 * Set VPN Status using string
	 * 
	 * @param string  $vpnStatus
	 * @return void
	 */
	public function setVpnStatusAttribute(string $vpnStatus)
	{
		if (is_numeric($vpnStatus)) {
			$this->attributes['vpn_status'] = (int) $vpnStatus;
			return;
		}

		$vpnStatus = preg_replace('~[\r\n]~', '', $vpnStatus);
        $vpnStatus = str_replace('\n', '', $vpnStatus);
        $status = (Status::fromKey(ucfirst($vpnStatus)))->value;
        $this->attributes['vpn_status'] = $status;
	}

	/**
	 * Get current VPN PID Numbers
	 * 
	 * @return array
	 */
	public function getCurrentVpnPidNumbersAttribute()
	{
		$vpnPidNumbers = $this->attributes['vpn_pid_numbers'];
		if (is_array($vpnPidNumbers)) {
			return $vpnPidNumbers;
		}

		if (is_string($vpnPidNumbers)) {
			if (! $vpnPidNumbersArray = json_decode($vpnPidNumbers, true)) {
				return [$vpnPidNumbersArray];
			}

			return $vpnPidNumbersArray;
		}

		return [];
	}

	/**
	 * Set VPN PID Numbers using string
	 * 
	 * @param string  $vpnPidNumbers
	 * @return void
	 */
	public function setVpnPidNumbersAttribute(string $vpnPidNumbers)
	{
		if (! $vpnPidNumbers) {
            return;
        }

        $vpnPidNumbers = preg_replace('~[\r\n]~', '', $vpnPidNumbers);
        $vpnPidNumbers = explode(' ', $vpnPidNumbers);
        $vpnPidNumbers = json_encode($vpnPidNumbers);
        $this->attributes['vpn_pid_numbers'] = $vpnPidNumbers;
	}

	/**
	 * Get current vpn enability
	 * 
	 * @return int
	 */
	public function getCurrentVpnEnabilityAttribute()
	{
		return $this->attributes['vpn_enability'];
	}

	/**
	 * Set VPN Enability using string
	 * 
	 * @param string  $status
	 * @return void
	 */
	public function setVpnEnabilityAttribute(string $status)
	{
		if (is_numeric($status)) {
			$this->attributes['vpn_enability'] = (int) $status;
			return;
		}

		$statusKey = ucfirst($status);
        $status = (Enability::fromKey($statusKey))->value;
        $this->attributes['vpn_enability'] = $status;
	}
}