<div class="form-group">
    <label>Server Name</label>
    <div class="input-group">
        <input 
            name="server_name" 
            type="text" 
            class="form-control" 
            placeholder="Server Name">
    </div>
</div>

<div class="form-group">
    <label>Datacenter</label>
    <div class="input-group">
        <select class="form-control" name="datacenter_id">
            <option>Please Select</option>
            @foreach ($datacenters as $key => $datacenter)
                <option value="{{ $datacenter->id }}">
                    {{ $datacenter->datacenter_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label>IP Address</label>
    <div class="input-group">
        <input 
            name="ip_address" 
            type="text" 
            class="form-control" 
            placeholder="IP Address"> 
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