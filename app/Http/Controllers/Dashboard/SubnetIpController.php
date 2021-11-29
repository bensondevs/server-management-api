<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\SubnetIps\FindSubnetIpRequest;
use App\Http\Requests\SubnetIps\PopulateSubnetIpsRequest as PopulateRequest;
use App\Http\Requests\SubnetIps\SaveSubnetIpRequest as SaveRequest;

use App\Http\Resources\SubnetIpResource;

use App\Models\Subnet;
use App\Models\SubnetIp;

use App\Repositories\SubnetIpRepository;

class SubnetIpController extends Controller
{
    protected $ip;

    public function __construct(SubnetIpRepository $ip)
    {
    	$this->ip = $ip;
    }

    public function index(PopulateRequest $request, Subnet $subnet)
    {
        if ($request->ajax()) {
            $options = $request->options();

            $paginate = strtobool($request->paginate) ?: true;
            $subnetIps = $this->ip->all($options, $paginate);
            $subnetIps = $paginate ?
                SubnetIpResource::collection($subnetIps) :
                SubnetIpResource::apiCollection($subnetIps);

            return response()->json(['subnet_ips' => $subnetIps]);
        }

        return view('dashboard.subnets.ips.index', compact('subnet'));
    }

    public function assignableIps(PopulateRequest $request, Subnet $subnet)
    {
        if ($request->ajax()) {
            $options = $request->options();

            $subnetIps = $this->ip->assignables($options, true);
            $subnetIps = SubnetIpResource::apiCollection($subnetIps);

            return response()->json(['subnet_ips' => $subnetIps]);
        }

        return redirect()->back();
    }

    public function create(Subnet $subnet)
    {
    	return view('dashboard.subnets.ips.create', compact(['subnet']));
    }

    public function store(SaveRequest $request, Subnet $subnet)
    {
    	$input = $request->validated();
    	$ip = $this->ip->save($input);

        flash_repository($this->ip);

    	return redirect()->route('dashboard.subnets.ips.index', $subnet);
    }

    public function edit(Subnet $subnet, SubnetIp $ip)
    {
    	return view('dashboard.subnets.ips.edit', compact(['subnet', 'ip']));
    }

    public function switchForbidden(SubnetIp $ip)
    {
    	$ip = $this->ip->setModel($ip);
    	$ip = $this->ip->switchForbidden();
        $ip = new SubnetIpResource($ip);

    	return response()->json(['ip' => $ip]);
    }

    public function update(SaveRequest $request, Subnet $subnet, SubnetIp $ip)
    {
        $input = $request->validated();

    	$ip = $this->ip->setModel($ip);
    	$ip = $this->ip->save($input);

    	flash_repository($this->ip);

        $variables = ['subnet', 'ip'];
    	return redirect()->route('dashboard.subnets.ips.index', compact($variables));
    }

    public function delete(Subnet $subnet, SubnetIp $ip)
    {
    	return view('dashboard.subnets.ips.delete', compact(['subnet', 'ip']));
    }

    public function destroy(Subnet $subnet, SubnetIp $ip)
    {
        $this->ip->setModel($ip);
    	$this->ip->destroy();

    	flash_repository($this->ip);

        $variables = ['subnet', 'ip'];
    	return redirect()->route('dashboard.subnets.ips.index', compact($variables));
    }
}
