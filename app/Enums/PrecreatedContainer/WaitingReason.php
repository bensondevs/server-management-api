<?php

namespace App\Enums\PrecreatedContainer;

use BenSampo\Enum\Enum;

final class WaitingReason extends Enum
{
    /*
    |--------------------------------------------------------------------------
    | Waiting reason is due to insufficiency of SERVER
    |--------------------------------------------------------------------------
    */
    /**
     * Selected server for the container is not found
     * 
     * @var int
     */
    const ServerNotFound = 10;

    /**
     * Selected server for the container is currently
     * inactive
     * 
     * @var int
     */
    const ServerInactive = 11;

    /*
    |--------------------------------------------------------------------------
    | Waiting reason is due to insufficiency of SUBNET
    |--------------------------------------------------------------------------
    | All precreated container under this category of status will be retried
    | to be created whenever new subnet is created or there is new subnet that
    | has the status of "Free".
    |
    | All of this process will be working by the works of event and observer.
    */
    /**
     * Selected subnet is not found
     * 
     * @var int
     */
    const SubnetNotFound = 20;

    /**
     * Selected subnet is forbidden
     * 
     * @var int
     */
    const SubnetForbidden = 21;

    /**
     * Selected subnet is unavailable
     * 
     * @var int
     */
    const SubnetUnavailable = 22;

    /**
     * No subnet is available
     * 
     * @var int
     */
    const NoSubnetAvailable = 23;

    /*
    |--------------------------------------------------------------------------
    | Waiting reason is due to insufficiency of SUBNET-IP
    |--------------------------------------------------------------------------
    | All pre-created container that falls into this category of waiting, will be
    | retried each time new subnet ip is created or whenever there is subnet ip
    | that's set to "Available".
    |
    | All of this process will be working by the works of event and observer.
    */
    /**
     * Selected subnet ip does not exists.
     * This possibly because of malformed input.
     * 
     * @var int
     */
    const InvalidSubnetIp = 30;

    /**
     * Selected subnet ip is already assigned to other user
     * 
     * @var int
     */
    const SubnetIpAssigned = 31;

    /**
     * Selected subnet ip is forbidden
     * 
     * @var int
     */
    const SubnetForbidden = 32;

    /**
     * No subnet IP is available
     * 
     * @var int
     */
    const NoSubnetIpAvailable = 33;

    /*
    |--------------------------------------------------------------------------
    | Waiting reason is due to other reason
    |--------------------------------------------------------------------------
    */
    /**
     * Server failure in handling request.
     * 
     * This will set the pre-created container to be retried all over again.
     * 
     * @var int
     */
    const InternalServerError = 500;

    /**
     * Custom reason, due to decision taken by admin
     * 
     * This reason needs more explanation on another column
     * of the pre-created container.
     * 
     * @var int
     */
    const OtherReason = 100;
}
