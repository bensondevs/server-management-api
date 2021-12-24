<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class ContainerResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $customer = $this->customer;

        $structure = [
            'id' => $this->id,

            'service_plan_id' => $this->service_plan_id,

            'user_id' => $this->user_id,

            'region_id' => $this->region_id,
            'datacenter_id' => $this->datacenter_id,
            'server_id' => $this->server_id,
            'subnet_id' => $this->subnet_id,
            'subnet_ip_id' => $this->subnet_ip_id,
            
            'hostname' => $this->hostname,
            'client_email' => $this->client_email,

            'status' => $this->status,
            'status_description' => $this->status_description,
            
            'disk_space' => $this->disk_space,
            'disk_array' => $this->disk_array,
            'breakpoints' => $this->breakpoints,

            'current' => $this->current,

            'order_date' => $this->order_human_date,
            'activation_date' => $this->activation_human_date,
            'expiration_date' => $this->expiration_human_date,
        ];

        if ($this->relationLoaded('user')) {
            $structure['user'] = new UserResource($this->user);
            $structure['user_full_name'] = $structure['user']->full_name;

            if (! $structure['client_email']) {
                $structure['client_email'] = $structure['user']->email;
            }
        }

        if ($this->relationLoaded('server')) {
            $structure['server'] = new ServerResource($this->server);
            $structure['server_name'] = $this->server->complete_server_name;
        }

        if ($this->relationLoaded('subnet')) {
            $structure['subnet'] = new SubnetResource($this->subnet);
        }

        if ($this->relationLoaded('subnetIp')) {
            $structure['subnet_ip'] = new SubnetIpResource($this->subnetIp);
            $structure['ip_address'] = $structure['subnet_ip']->ip_address;
        }

        if ($this->relationLoaded('servicePlan')) {
            $structure['service_plan'] = new ServicePlanResource($this->servicePlan);
        }

        if ($this->relationLoaded('order')) {
            $structure['order'] = new OrderResource($this->order);
        }

        if ($this->relationLoaded('vpnUsers')) {
            $structure['vpn_users'] = $this->vpnUsers;
        }

        if ($this->relationLoaded('nfsExports')) {
            $structure['nfs_exports'] = $this->nfsExports;
        }

        if ($this->relationLoaded('nginxLocations')) {
            $structure['nginx_locations'] = $this->nginxLocations;
        }

        if ($this->relationLoaded('sambaShares')) {
            $structure['samba_shares'] = $this->sambaShares;
        }

        return $structure;
    }
}