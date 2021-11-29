<div class="form-group">
    <label class="col-form-label">
        Invoice code:
    </label>
    <input 
        type="disabled" 
        class="form-control" 
        name="invoice_code" 
        readonly>
</div>

<div class="form-group">
    <label class="col-form-label">
        Nama Pembeli:
    </label>
    <input 
        type="text" 
        class="form-control" 
        name="buyer_name" 
        readonly>
</div>

<div class="form-group">
    <label class="col-form-label">
        Kasir:
    </label>
    <input 
        type="text" 
        class="form-control" 
        name="creator_name" 
        readonly>
</div>

<div class="form-group">
    <label class="col-form-label">
        Status:
    </label>
    <p id="invoice_status"></p>
</div>

<div class="form-group">
    <input
        type="hidden" 
        class="form-control" 
        name="transaction_time"
        autocomplete="off"
        readonly>
</div>