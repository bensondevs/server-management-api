<div class="form-group">
    <label class="col-form-label">
        Produk
    </label>
    <select class="form-control" name="product_id">
        @foreach (App\Models\Product::orderBy('product_name')->get() as $key => $product)
            <option value="{{ $product->id }}">
                {{ $product->supplier->company }} - {{ $product->product_name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label class="col-form-label">
        Jumlah Penyesuaian
    </label>
    <input type="number" class="form-control" name="adjustment_amount">
</div>

<div class="form-group">
    <label class="col-form-label">
        Alasan Penyesuaian
    </label>
    <input 
        type="text" 
        class="form-control" 
        name="adjustment_note" 
        required>
</div>