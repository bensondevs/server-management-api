<div class="form-group">
    <label>First Name</label>
    <div class="input-group">
        <input 
            name="first_name" 
            type="text" 
            class="form-control" 
            placeholder="First Name">
    </div>
</div>

<div class="form-group">
    <label>Middle Name (Optional)</label>
    <div class="input-group">
        <input 
            name="middle_name" 
            type="text" 
            class="form-control" 
            placeholder="Middle Name (Optional)">
    </div>
</div>

<div class="form-group">
    <label>Last Name</label>
    <div class="input-group">
        <input 
            name="last_name" 
            type="text" 
            class="form-control" 
            placeholder="Last Name">
    </div>
</div>

<div class="form-group">
    <label>Address</label>
    <div class="input-group">
        <textarea class="form-control" name="address"></textarea>
    </div>
</div>

<div class="form-group">
    <label>Country</label>
    <div class="input-group">
        <select class="form-control" name="country">
            @foreach (\App\Models\Country::all() as $country)
                <option value="{{ $country['country_name'] }}">{{ $country['country_name'] }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label>Company Name (Optional)</label>
    <div class="input-group">
        <input 
            name="last_name" 
            type="text" 
            class="form-control" 
            placeholder="Company Name (Optional)">
    </div>
</div>

<div class="form-group">
    <label>Username</label>
    <div class="input-group">
        <input 
            name="username" 
            type="text" 
            class="form-control" 
            placeholder="Username">
    </div>
</div>

<div class="form-group">
    <label>Email</label>
    <div class="input-group">
        <input 
            name="email" 
            type="text" 
            class="form-control" 
            placeholder="Email">
    </div>
</div>

<div class="form-group">
    <label>Password</label>
    <div class="input-group">
        <input 
            name="password" 
            type="password" 
            class="form-control" 
            placeholder="Password"> 
    </div>
</div>

<div class="form-group">
    <label>Confirm Password</label>
    <div class="input-group">
        <input 
            name="confirm_password" 
            type="password" 
            class="form-control" 
            placeholder="Confirm Password"> 
    </div>
</div>

<div class="form-group">
    <label>Balance</label>
    <div class="input-group">
        <input 
            name="balance" 
            type="number" 
            min="0"
            class="form-control" 
            placeholder="Balance"
            value="0">
    </div>
</div>