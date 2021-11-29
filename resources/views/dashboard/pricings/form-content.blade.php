<div class="form-group">
    <label>Currency</label>
    <div class="input-group">
        <select class="form-control" name="currency">
            @foreach ($options as $key => $currency)
                <option value="{{ $key }}">
                    {{ strtoupper($currency) }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label>Price</label>
    <div class="input-group">
        <input class="form-control" type="text" name="price">
    </div>
</div>