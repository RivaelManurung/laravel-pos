<div class="row g-3">
    <div class="col-md-6">
        <label for="edit-name" class="form-label">Nama Produk</label>
        <input type="text" id="edit-name" name="name" class="form-control" value="" required>
    </div>
    <div class="col-md-6">
        <label for="edit-sku" class="form-label">SKU</label>
        <input type="text" id="edit-sku" name="sku" class="form-control" value="">
    </div>
    <div class="col-md-6">
        <label for="edit-barcode" class="form-label">Barcode</label>
        <input type="text" id="edit-barcode" name="barcode" class="form-control" value="">
    </div>
    <div class="col-md-6">
        <label for="edit-category_id" class="form-label">Kategori</label>
        <select id="edit-category_id" name="category_id" class="form-select" required>
            <option value="">Pilih Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="edit-price" class="form-label">Harga</label>
        <input type="number" step="0.01" id="edit-price" name="price" class="form-control" value="" required>
    </div>
    <div class="col-md-6">
        <label for="edit-cost" class="form-label">Biaya</label>
        <input type="number" step="0.01" id="edit-cost" name="cost" class="form-control" value="">
    </div>
    <div class="col-md-4">
        <label for="edit-stock" class="form-label">Stok</label>
        <input type="number" id="edit-stock" name="stock" class="form-control" value="" required>
    </div>
    <div class="col-md-4">
        <label for="edit-min_stock" class="form-label">Min Stok</label>
        <input type="number" id="edit-min_stock" name="min_stock" class="form-control" value="">
    </div>
    <div class="col-md-4">
        <label for="edit-is_active" class="form-label">Status</label>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="edit-is_active" name="is_active" value="1">
            <label class="form-check-label" for="edit-is_active">Aktif</label>
        </div>
    </div>
    <div class="col-12">
        <label for="edit-description" class="form-label">Deskripsi</label>
        <textarea id="edit-description" name="description" class="form-control"></textarea>
    </div>
    <div class="col-12">
        <label for="edit-image" class="form-label">Gambar</label>
        <input type="file" id="edit-image" name="image" class="form-control">
        <img id="edit-image-preview" src="" alt="Preview" style="display: none; width: 100px; margin-top: 10px;">
    </div>
</div>
