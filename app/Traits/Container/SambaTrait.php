<?php

namespace App\Traits\Container;

use App\Enums\Container\Samba\{
	ContainerSambaSmbdStatus as SmbdStatus,
	ContainerSambaNmbdStatus as NmbdStatus, 
	ContainerSambaSmbdEnability as SmbdEnability,
	ContainerSambaNmbdEnability as NmbdEnability
};

use App\Models\{
	SambaDirectory as Directory,
	SambaShare as Share,
	SambaGroup as Group,
	SambaUser as User,
};

trait SambaTrait 
{
	/**
	 * Get Container existing samba directories
	 */
	public function sambaDirectories()
	{
		return $this->hasMany(Directory::class);
	}

	/**
	 * Get container existing samba shares
	 */
	public function sambaShares()
    {
        return $this->hasMany(Share::class);
    }

    /**
     * Get container existing samba groups
     */
    public function sambaGroups()
    {
        return $this->hasMany(Group::class);
    }

    /**
     * Get container existing samba users
     */
    public function sambaUsers()
    {
    	return $this->hasMany(User::class);
    }

	/**
	 * Get current samba statuses
	 * 
	 * @return array
	 */
	public function getCurrentSambaStatusAttribute()
	{
		return [
			'nmbd' => $this->current_samba_smbd_status,
			'smbd' => $this->current_samba_nmbd_status,
		];
	}

	/**
	 * Get current samba smbd status
	 * 
	 * @return int
	 */
	public function getCurrentSambaSmbdStatusAttribute()
	{
		if (! $smbdStatus = $this->attributes['samba_smbd_status']) {
			$smbdStatus = SmbdStatus::Requesting;
		}

		return $smbdStatus;
	}

	/**
	 * Get current samba nmbd status
	 * 
	 * @return int
	 */
	public function getCurrentSambaNmbdStatusAttribute()
	{
		if (! $nmbdStatus = $this->attributes['samba_nmbd_status']) {
			$nmbdStatus = NmbdStatus::Requesting;
		}

		return $nmbdStatus;
	}

	/**
	 * Set samba smbd status using string
	 * 
	 * @param string  $smbdStatus
	 * @return void
	 */
	public function setSambaSmbdStatusAttribute(string $smbdStatus)
	{
		$smbdStatus = preg_replace('~[\r\n]~', '', $smbdStatus);
		$smbdStatus = str_replace('\n', '', $smbdStatus);
		$enum = (SmbdStatus::fromKey(ucfirst($smbdStatus)));
        $status = isset($enum->value) ? $enum->value : SmbdStatus::Unknown;
        $this->attributes['samba_smbd_status'] = $status;
	}

	/**
	 * Set samba nmbd status using string
	 * 
	 * @param string  $nmbdStatus
	 * @return void
	 */
	public function setSambaNmbdStatusAttribute(string $nmbdStatus)
	{
		$nmbdStatus = preg_replace('~[\r\n]~', '', $nmbdStatus);
		$nmbdStatus = str_replace('\n', '', $nmbdStatus);
		$enum = (NmbdStatus::fromKey(ucfirst($nmbdStatus)));
        $status = isset($enum->value) ? $enum->value : NmbdStatus::Unknown;
        $this->attributes['samba_nmbd_status'] = $status;
	}

	/**
	 * Get current samba PID Numbers
	 * 
	 * @return int
	 */
	public function getCurrentSambaPidNumbersAttribute()
	{
		$sambaPidNumbers = $this->attributes['samba_pid_numbers'];
		if (is_array($sambaPidNumbers)) {
			return $sambaPidNumbers;
		}

		if (is_string($sambaPidNumbers)) {
			if (! $sambaPidNumbersArray = json_decode($sambaPidNumbers, true)) {
				return [$sambaPidNumbers];
			}

			return $sambaPidNumbersArray;
		}

		return $sambaPidNumbers;
	}

	/**
	 * Set samba pid numbers
	 * 
	 * @param string  $sambaPidNumbers
	 * @return void
	 */
	public function setSambaPidNumbersAttribute(string $sambaPidNumbers)
	{
		if (! $sambaPidNumbers) {
			return;
		}

		$sambaPidNumbers = preg_replace('~[\r\n]~', '', $sambaPidNumbers);
		$sambaPidNumbers = explode(' ', $sambaPidNumbers);
		$sambaPidNumbers = json_encode($sambaPidNumbers);
		$this->attributes['samba_pid_numbers'] = $sambaPidNumbers;
	}

	/**
	 * Get current samba smbd and nmbd enability
	 * 
	 * @return array
	 */
	public function getCurrentSambaEnabilityAttribute()
	{
		return [
			'smbd' => $this->attributes['samba_smbd_enability'],
			'nmbd' => $this->attributes['samba_nmbd_enability'],
		];
	}

	/**
	 * Get current samba smbd enability
	 * 
	 * @return int
	 */
	public function getCurrentSambaSmbdEnabilityAttribute()
	{
		return $this->attributes['samba_smbd_enability'];
	}

	/**
	 * Get current samba nmbd enability
	 * 
	 * @return int
	 */
	public function getCurrentSambaNmbdEnabilityAttribute()
	{
		return $this->attributes['samba_nmbd_enability'];
	}

	/**
	 * Set samba smbd enability
	 * 
	 * @param string  $status
	 * @return void
	 */
	public function setSambaSmbdEnabilityAttribute(string $status)
	{
		$statusKey = ucfirst($status);
		$status = (SmbdEnability::fromKey($statusKey))->value;
		$this->attributes['samba_smbd_enability'] = $status;
	}

	/**
	 * Set samba nmbd enability
	 * 
	 * @param string  $status
	 * @return void
	 */
	public function setSambaNmbdEnabilityAttribute(string $status)
	{
		$statusKey = ucfirst($status);
		$status = (NmbdEnability::fromKey($statusKey))->value;
		$this->attributes['samba_nmbd_enability'] = $status;
	}
}