<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\ServiceAddonResource;

use App\Models\Currency;
use App\Models\ServiceAddon;

use App\Repositories\ServiceAddonRepository as AddonRepository;

class ServiceAddonController extends Controller
{
    protected $addon;

    public function __construct(AddonRepository $addonRepository)
    {
    	$this->addon = $addonRepository;
    }

    public function index()
    {
    	return view('dashboard.service_addons.index');
    }

    public function populate()
    {
        $addons = $this->addon->all();
        $addons = $this->addon->paginate();
        $addons->data = ServiceAddonResource::collection($addons);

        return response()->json(['addons' => $addons]);
    }

    public function create()
    {
        $currencies = Currency::all();

    	return view(
            'dashboard.service_addons.create',
            compact(['currencies'])
        );
    }

    public function store(SaveServiceAddonRequest $request)
    {
    	$input = $request->onlyInRules();
    	$this->addon->save($input);

    	flashMessage($this->addon);

    	return redirect()->route('dashboard.service_addons.index');
    }

    public function edit(FindServiceAddonRequest $request)
    {
    	$addon = $this->addon->find($request->input('id'));
        $currencies = Currency::all();

    	return view(
            'dashboard.service_plans.edit', 
            compact(['addon', 'currencies'])
        );
    }

    public function update(SaveServiceAddonRequest $request)
    {
    	$this->addon->find($request->input('id'));
    	$this->addon->save($request->onlyInRules());

    	flashMessage($this->addon);

    	return redirect()->route('dashboard.service_addons.index');
    }

    public function confirmDelete(FindServiceAddonRequest $request)
    {
    	$addon = $this->addon->find($request->input('id'));

    	return view(
    		'dashboard.service_addons.confirm-delete', 
    		compact('addon')
    	);
    }

    public function delete(FindServiceAddonRequest $request)
    {
    	$this->addon->find($request->input('id'));
    	$this->addon->delete();

    	flashMessage($this->addon);

    	return redirect()->route('dashboard.service_addons.index');
    }
}
