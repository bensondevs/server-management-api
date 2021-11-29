<div class="form-group">
    <label>Plan Name</label>
    <div class="input-group">
        <input 
            name="plan_name" 
            type="text" 
            class="form-control" 
            placeholder="Plan Name">
    </div>
</div>

<div class="form-group">
    <label>Plan Code</label>
    <div class="input-group">
        <input 
            name="plan_code" 
            type="text" 
            class="form-control" 
            placeholder="Plan Code">
    </div>
</div>

<div class="form-group">
    <label>Time Quantity</label>
    <div class="input-group">
        <input 
            name="time_quantity" 
            type="number" 
            class="form-control" 
            placeholder="Time Quantity">
    </div>
</div>

<div class="form-group">
    <label>Time Unit</label>
    <div class="input-group">
        <select class="form-control" name="time_unit">
        	@foreach (\App\Enums\ServicePlan\ServicePlanTimeUnit::asSelectArray() as $code => $timeUnit)
                <option value="{{ $code }}">{{ $timeUnit }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label>Description</label>
    <div class="col-lg-9 col-md-9 col-sm-12">
        <textarea class="summernote form-control" rows="10" name="description"></textarea>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('assets/js/pages/crud/forms/editors/summernote.js') }}"></script>