<div class="form-group">
    <label>Addon Name</label>
    <div class="input-group">
        <input 
            name="addon_name" 
            type="text" 
            class="form-control" 
            placeholder="Service Addon Name">
    </div>
</div>

<div class="form-group">
    <label>Addon Type</label>
    <div class="input-group">
        <select class="form-control" name="addon_type">
        	<option value="duration">Duration</option>
        	<option value="speed">Speed</option>
        	<option value="space">Space</option>
        </select>
    </div>
</div>

<div class="form-group">
    <label>Currency</label>
    <div class="input-group">
        <select class="form-control" name="currency">
        	@foreach ($currencies as $currency)
        		<option 
        			value="{{ $currency->currency_code }}" 
        			@if ($currency->currency_code == 'EUR') selected @endif>
        			{{ $currency->currency_code }} - {{ $currency->currency_name }}
        		</option>
        	@endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label>Addon Fee</label>
    <div class="input-group">
        <input 
            name="addon_fee" 
            type="number" 
            class="form-control" 
            placeholder="Service Addon Fee">
    </div>
</div>

<div class="form-group">
    <label>Quantity</label>
    <div class="input-group">
        <input 
            name="quantity" 
            type="number" 
            class="form-control" 
            placeholder="Quantity">
    </div>
</div>

<div class="form-group">
    <label>Unit</label>
    <div class="input-group">
        <input 
            name="quantity" 
            type="text" 
            class="form-control" 
            placeholder="Unit">
    </div>
</div>

<div class="form-group">
    <label>Description</label>
    <div class="input-group">
        <textarea 
        	class="form-control"
        	name="description"
        	rows="10" 
        	placeholder="Description"></textarea>
    </div>
</div>