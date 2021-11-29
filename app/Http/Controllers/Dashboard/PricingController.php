<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Pricings\SavePricingRequest;

use App\Repositories\PricingRepository;

class PricingController extends Controller
{
    private $pricing;

    public function __construct(PricingRepository $pricing)
    {
        $this->pricing = $pricing;
    }

    public function create(Request $request)
    {
        $options = $this->pricing->currencyOptions();
        $priceableId = $request->priceable_id;
        $priceableType = $request->priceable_type;
        return view('dashboard.pricings.create', compact(['options', 'priceableId', 'priceableType']));
    }

    public function store(SavePricingRequest $request)
    {
        $input = $request->validated();
        $this->pricing->save($input);

        flash_repository($this->pricing);

        return redirect()->back();
    }

    public function edit(Request $request)
    {
        $pricing = $this->pricing->find($request->id);
        $options = $this->pricing->currencyOptions();
        return view('dashboard.pricings.edit', compact(['pricing', 'options']));
    }

    public function update(SavePricingRequest $request)
    {
        $pricing = $request->getPricing();
        $this->pricing->setModel($pricing);

        $input = $request->validated();
        $this->pricing->save($input);

        flash_repository($this->pricing);

        return redirect()->back();
    }

    public function confirmDelete(Request $request)
    {
        $pricing = $this->pricing->find($request->id);
        return view('dashboard.pricings.confirm-delete', compact('pricing'));
    }

    public function delete(Request $request)
    {
        $this->pricing->find($request->id);
        $this->pricing->delete();

        flash_repository($this->pricing);

        return redirect()->route('dashboard.service_plans.index');
    }
}