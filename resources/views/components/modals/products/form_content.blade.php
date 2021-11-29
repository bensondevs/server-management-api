<div class="form-group">
    <label class="col-form-label">
        Foto Produk
    </label>
    <input type="file" class="form-control" name="product_image">
</div>

<div class="form-group col-4">
    <img name="image_preview" class="img-square img-fluid">
</div>

<div class="form-group">
    <label class="col-form-label">
        Nama Produk:
    </label>
    <input 
        type="text" 
        class="form-control" 
        name="product_name" 
        required>
</div>

<div class="form-group">
    <label class="col-form-label">
        Tipe:
    </label>
    <input
        id="type_input"
        type="text" 
        class="form-control" 
        name="type"
        autocomplete="off"
        required>
</div>

<div class="form-group">
    <label class="col-form-label">
        Satuan:
    </label>
    <input 
        type="text" 
        class="form-control" 
        name="satuan" 
        required>
</div>

<div class="form-group">
    <label class="col-form-label">
        Supplier:
    </label>
    
    <select class="form-control selectpicker" name="supplier_id" data-live-search="true" required>
        @foreach (App\Models\Supplier::all() as $key => $supplier)
            <option value="{{ $supplier->id }}">
                {{ $supplier->supplier_name}} - {{ $supplier->company }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label class="col-form-label">
        Jumlah Minimum: (ISI 0 JIKA PRODUK INI AKAN MEMILIKI RESEP!)
    </label>
    <input 
        type="number" 
        class="form-control" 
        name="minimum_stock" 
        value="0"
        required>
</div>

<div class="form-group">
    <label class="col-form-label">
        Harga Beli: 
    </label>
    <input 
        type="number" 
        class="form-control" 
        name="buying_price" 
        value="0"
        required>
</div>

<div class="form-group">
    <label class="col-form-label">
        Harga Jual: 
    </label>
    <input 
        type="number" 
        class="form-control" 
        name="selling_price" 
        value="0"
        required>
</div>