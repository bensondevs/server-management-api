<div class="form-group">
    <label>Service Plan</label>
    <div class="input-group">
        <select class="form-control" name="service_plan_id">
            @foreach ($plans as $plan)
                <option value="{{ $plan->id }}">
                    {{ $plan->plan_name }} ({{ $plan->duration }})
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label>Service Plan Quantity</label>
    <div class="input-group">
        <input class="form-control" type="number" name="quantity" />
    </div>
</div>

<div class="form-group">
    <label>Note (Optional)</label>
    <div class="input-group">
        <textarea class="form-control" name="note"></textarea>
    </div>
</div>

<div class="form-group">
    <label>Datacenter</label>
    <div class="input-group">
        <select class="form-control" name="datacenter_id">
            @foreach ($datacenters as $datacenter)
                <option value="{{ $datacenter->id }}">
                    {{ $datacenter->datacenter_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label>Hostname</label>
    <div class="input-group">
        <input class="form-control" type="text" name="hostname" />
    </div>
</div>

<div class="form-group">
    <label>Customer</label>
    <div class="input-group">
        <select class="form-control" name="customer_id">
            @foreach ($customers as $key => $customer)
                <option value="{{ $customer->id }}">
                    {{ $customer->email }} | {{ $customer->full_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label>Status</label>
    <div class="input-group">
        <select class="form-control" name="status">
        	@foreach (\App\Models\Order::ORDER_STATUS as $status)
                <option value="{{ $status['value'] }}">
                    {{ $status['label'] }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label>Customer</label>
    <div class="input-group">
    	<select 
    		class="form-control" 
    		name="customer_id" 
    		aria-hidden="true">
    		@foreach ($customers as $customer)
        		<option value="{{ $customer->id }}">
        			{{ $customer->email }} - {{ $customer->username }} - {{ $customer->full_name}}
        		</option>
        	@endforeach 
		</select>
    </div>
</div>

<div class="form-group">
    <label>VAT Size Percentage (%)</label>
    <div class="input-group">
        <input 
        	class="form-control" 
        	type="text" 
        	placeholder="10" 
        	name="vat_size_percentage" />
    </div>
</div>