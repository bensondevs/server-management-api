<div class="form-group">
    <label class="col-form-label">
        Foto User
    </label>
    <input 
        type="file" 
        class="form-control" 
        name="profile_picture">
</div>

<div class="form-group">
    <label class="col-form-label">
        Hak Akses
    </label>
    <select 
        class="form-control"
        name="role">
        <option 
            value="owner">
            Owner
        </option>
        <option
            value="cashier">
            Kasir
        </option>
        <option
            value="inventory">
            Inventory
        </option>
    </select>
</div>

<div class="form-group">
    <label class="col-form-label">
        Nama Lengkap:
    </label>
    <input 
        type="text" 
        class="form-control" 
        name="name" 
        required>
</div>

<div class="form-group">
    <label class="col-form-label">
        Email:
    </label>
    <input 
        type="text" 
        class="form-control" 
        name="email" 
        required>
</div>

<div class="form-group">
    <label class="col-form-label">
        Tanggal Lahir
    </label>
    <input 
        type="date" 
        class="form-control" 
        name="birth_date" 
        required>
</div>

<div class="form-group">
    <label class="col-form-label">
        Jenis Kelamin
    </label>
    <select 
        class="form-control"
        name="gender">
        <option 
            value="L">
            Laki-laki
        </option>
        <option
            value="P">
            Perempuan
            </option>
    </select>
</div>

<div class="form-group">
    <label class="col-form-label">
        Nomor Telepon:
    </label>
    <input 
        type="text" 
        class="form-control" 
        name="phone_number" 
        required>
</div>

<div class="form-group">
    <label class="col-form-label">
        Alamat:
    </label>
    <input 
        type="text" 
        class="form-control" 
        name="address" 
        required>
</div>

<div class="form-group">
    <label class="col-form-label">
        Password (Minimal: 8 karakter)
    </label>
    <input 
        type="password" 
        class="form-control" 
        name="password">
</div>

<div class="form-group">
    <label class="col-form-label">
        Konfirmasi Password (Minimal: 8 karakter)
    </label>
    <input 
        type="password" 
        class="form-control" 
        name="confirm_password">
</div>