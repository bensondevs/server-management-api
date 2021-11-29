<div class="form-group">
    <label>Datacenter Name</label>
    <div class="input-group">
        <input 
            name="datacenter_name" 
            type="text" 
            class="form-control" 
            placeholder="Datacenter Name">
    </div>
</div>

<div class="form-group">
    <label>Datacenter Name (for client)</label>
    <div class="input-group">
        <input 
            name="client_datacenter_name" 
            type="text" 
            class="form-control" 
            placeholder="Datacenter Name for Client"> 
    </div>
</div>

<div class="form-group">
    <label>Datacenter Region</label>
    <div class="input-group">
        <select 
            name="region_id"
            class="form-control">
            @foreach ($regions as $key => $region)
                <option value="{{ $region->id }}">
                    {{ $region->region_name }}
                </option>
            @endforeach      
        </select>
    </div>
</div>

<div class="form-group">
    <label>Datacenter Location</label>
    <div class="input-group">
        <input 
            name="location" 
            type="text" 
            class="form-control" 
            placeholder="Datacenter Location"> 
    </div>
</div>

<div class="form-group">
    <label>Status</label>
    <div class="input-group">
        <select 
            name="status"
            class="form-control">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>        
        </select>
    </div>
</div>