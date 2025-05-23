<?php

namespace App\Traits\Container;

use App\Enums\Container\Nfs\{
    ContainerNfsStatus as Status,
    ContainerNfsEnability as Enability,
    ContainerNfsBindPublicIp as BindPublicIp
};

use App\Models\{
    NfsExport as Export,
    NfsFolder as Folder
};

trait NfsTrait 
{
    /**
     * Container has many NFS Exports
     */
    public function nfsExports()
    {
        return $this->hasMany(Export::class);
    }

    /**
     * Container has many NFS Folders
     */
    public function nfsFolders()
    {
        return $this->hasMany(Folder::class);
    }

    /**
     * Set NFS status requesting
     * 
     * @return bool
     */
    public function setNfsStatusRequesting()
    {
        $this->attributes['nfs_status'] = Status::Requesting;
        return $this->save();
    }

    /**
     * Get Current NFS Status
     * 
     * @return int
     */
    public function getCurrentNfsStatusAttribute()
    {
        if (! $nfsStatus = $this->attributes['nfs_status']) {
            $nfsStatus = Status::Requesting;  
        }

        return $nfsStatus;
    }

    /**
     * Set NFS Status
     * 
     * @param string  $nfsStatus
     * @return void
     */
    public function setNfsStatusAttribute(string $nfsStatus)
    {
        if (is_numeric($nfsStatus)) {
            $this->attributes['nfs_status'] = (int) $nfsStatus;
            return;
        }

        $nfsStatus = preg_replace('~[\r\n]~', '', $nfsStatus);
        $nfsStatus = str_replace('\n', '', $nfsStatus);
        $enum = (Status::fromKey(ucfirst($nfsStatus)));
        $status = isset($enum->value) ? $enum->value : Status::Unknown;
        $this->attributes['nfs_status'] = $status;
    }

    /**
     * Get current NFS PID Numbers
     * 
     * @return array
     */
    public function getCurrentNfsPidNumbersAttribute()
    {
        $nfsPidNumbers = $this->attributes['nfs_pid_numbers'];
        if (is_array($nfsPidNumbers)) {
            return $nfsPidNumbers;
        }

        if (is_string($nfsPidNumbers)) {
            if (! $nfsPidNumbersArray = json_decode($nfsPidNumbers, true)) {
                return [$nfsPidNumbers];
            }

            return $nfsPidNumbersArray;
        }

        return [];
    }

    /**
     * Set NFS PID Numbers
     * 
     * @param array  $nfsPidNumbers
     * @return void
     */
    public function setNfsPidNumbersAttribute(array $nfsPidNumbers = [])
    {
        if (! $nfsPidNumbers) {
            return;
        }

        if (is_string($nfsPidNumbers)) {
            $nfsPidNumbers = preg_replace('~[\r\n]~', '', $nfsPidNumbers);
            $nfsPidNumbers = explode(' ', $nfsPidNumbers);
        }

        $nfsPidNumbers = json_encode($nfsPidNumbers);
        $this->attributes['nfs_pid_numbers'] = $nfsPidNumbers;
    }

    /**
     * Set NFS Enability as requesting
     * 
     * @return bool
     */
    public function setNfsEnabilityRequesting()
    {
        $this->attributes['nfs_enability'] = Enability::Requesting;
        return $this->save();
    }

    /**
     * Get current NFS Enability
     * 
     * @return int
     */
    public function getCurrentNfsEnabilityAttribute()
    {
        return $this->attributes['nfs_enability'];
    }

    /**
     * Set NFS Enability
     * 
     * @param string  $enability
     * @return void
     */
    public function setNfsEnabilityAttribute(string $enability)
    {
        if (is_numeric($enability)) {
            $this->attributes['nfs_enability'] = (int) $enability;
            return;
        }

        $statusKey = ucfirst($status);
        $status = (Enability::fromKey($statusKey))->value;
        $this->attributes['nfs_enability'] = $status;
    }

    /**
     * Get NFS bind to public IP
     * 
     * @return int
     */
    public function getCurrentNfsBindPublicIpAttribute()
    {
        return $this->attributes['nfs_bind_public_ip'];
    }

    /**
     * Set NFS bind to public IP
     * 
     * @param  string  $bindPublicIp
     * @return void
     */
    public function setNfsBindPublicIpAttribute(string $bindPublicIp)
    {
        if (is_numeric($bindPublicIp)) {
            $this->attributes['nfs_bind_public_ip'] = (int) $bindPublicIp;
            return;
        }

        $statusKey = ucfirst($bindPublicIp);
        $status = (BindPublicIp::fromKey($statusKey))->value;
        $this->attributes['nfs_bind_public_ip'] = $status;
    }
}