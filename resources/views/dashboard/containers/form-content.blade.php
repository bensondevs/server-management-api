<div class="form-group">
    <label>Service Plan</label>
    <div class="input-group">
        <select name="service_plan_id" class="form-control">
            <option value="">---Please Select---</option>
            @foreach ($plans ?? [] as $plan)
                <option value="{{ $plan->id }}">
                    {{ $plan->plan_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label>Customer</label>
    <div class="input-group">
        <select name="customer_id" class="form-control">
        	<option value="">---Please Select---</option>
        	@foreach ($customers as $customer)
        		<option value="{{ $customer->id }}">
        			{{ $customer->full_name }}
        		</option>
        	@endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label>Server</label>
    <div class="input-group">
        <select name="server_id" class="form-control">
        	<option>---Please Select---</option>
        	@foreach ($servers as $server)
        		<option value="{{ $server->id }}">
        			{{ $server->server_name }}
        		</option>
        	@endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label>Subnet</label>
    <div class="input-group">
        <select name="subnet_id" class="form-control">
        	<option>---Please Select---</option>
        	@foreach ($subnets as $subnet)
        		<option value="{{ $subnet->id }}">
        			{{ $subnet->subnet_mask }}
        		</option>
        	@endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label>Subnet IP</label>
    <div class="input-group">
        <select name="subnet_ip_id" class="form-control">
            <option>---Please Select---</option>
            
        </select>
    </div>
</div>

<div class="form-group">
    <label>Hostname</label>
    <div class="input-group">
        <input class="form-control" type="text" name="hostname">
    </div>
</div>

<div class="form-group">
    <label>Client Email</label>
    <div class="input-group">
        <input class="form-control" type="text" name="client_email">
    </div>
</div>

<div class="form-group">
    <label>Order Date</label>
    <div class="input-group">
        <input class="form-control" type="date" name="order_date">
    </div>
</div>

<div class="form-group">
    <label>Activation Date</label>
    <div class="input-group">
        <input class="form-control" type="date" name="activation_date">
    </div>
</div>

<div class="form-group">
    <label>Expiration Date</label>
    <div class="input-group">
        <input class="form-control" type="date" name="expiration_date">
    </div>
</div>

<script
  src="https://code.jquery.com/jquery-3.6.0.slim.min.js"
  integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI="
  crossorigin="anonymous"></script>

<script type="text/javascript">
    function populateIps(subnet_id, current_ip_id = '')
    {
        $.ajax({
            type: 'GET',
            url: '/dashboard/subnets/' + subnet_id + '/ips?paginate=false&per_page=1000',
            cache: false,
            dataType: 'JSON',
            success: function(response) {
                var data = JSON.parse(JSON.stringify(response))
                var ips = data.subnet_ips

                console.log(ips)

                let options = '<option>---Please Select---</option>'
                for (let index = 0; index < ips.length; index++) {
                    let ip = ips[index]

                    options += '<option value="' + ip.id + '"'
                    options += ' ' + (((ip.is_forbidden) && current_ip_id != ip.id) ? 'disabled' : '')
                    if (current_ip_id) {
                        options += ' ' + ((current_ip_id == ip.id) ? 'selected' : '') 
                    }
                    options += '>'

                    options += ip.ip_address
                    options += '</option>'
                }

                $('select[name=subnet_ip_id]').html(options)
            }
        })
    }

    // Auto Select Customer
    let selectedCustomerId = '{{ request()->get('customer_id') }}'
    if (selectedCustomerId)
        $('select[name=customer_id]').val(selectedCustomerId)

    // Populate IP(s)
    $('select[name=subnet_id]').on('change', function () {
        console.log(this.value)
        populateIps(this.value)
    })
</script>