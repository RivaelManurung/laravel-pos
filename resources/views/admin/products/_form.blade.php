<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Nama Produk</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label for="sku" class="form-label">SKU</label>
        <input type="text" id="sku" name="sku" class="form-control" value="{{ old('sku', $product->sku ?? '') }}">
    </div>
    <div class="col-md-6">
        <label for="barcode" class="form-label">Barcode</label>
        <input type="text" id="barcode" name="barcode" class="form-control" value="{{ old('barcode', $product->barcode ?? '') }}">
    </div>
    <div class="col-md-6">
        <label for="category_id" class="form-label">Kategori</label>
        <select id="category_id" name="category_id" class="form-select" required>
            <option value="">Pilih Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id ?? '') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="price" class="form-label">Harga</label>
        <input type="number" step="0.01" id="price" name="price" class="form-control" value="{{ old('price', $product->price ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label for="cost" class="form-label">Biaya</label>
        <input type="number" step="0.01" id="cost" name="cost" class="form-control" value="{{ old('cost', $product->cost ?? '') }}">
    </div>
    <div class="col-md-4">
        <label for="stock" class="form-label">Stok</label>
        <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock', $product->stock ?? 0) }}" required>
    </div>
    <div class="col-md-4">
        <label for="min_stock" class="form-label">Min Stok</label>
        <input type="number" id="min_stock" name="min_stock" class="form-control" value="{{ old('min_stock', $product->min_stock ?? 0) }}">
    </div>
    <div class="col-md-4">
        <label for="is_active" class="form-label">Status</label>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>
    <div class="col-12">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea id="description" name="description" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label for="image" class="form-label">Gambar</label>
        <input type="file" id="image" name="image" class="form-control">
    </div>
</div>
