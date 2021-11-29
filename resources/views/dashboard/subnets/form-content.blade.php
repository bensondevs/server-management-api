<div class="form-group">
    <label>Datacenter</label>
    <div class="input-group">
        <select 
            name="datacenter_id"
            class="form-control">
            @foreach ($datacenters as $key => $datacenter)
                <option value="{{ $datacenter->id }}">
                    {{ $datacenter->datacenter_name }}
                </option>
            @endforeach      
        </select>
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

<div class="form-group">
    <label>Subnet Mask</label>
    <div class="input-group">
        <input 
            class="form-control" 
            type="text" 
            placeholder="192.168.1.1/24" 
            name="subnet_mask">
    </div>
</div>