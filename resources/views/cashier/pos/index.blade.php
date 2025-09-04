@extends('layout.main')

@section('title', 'POS - Point of Sale')

@push('styles')
<style>
.pos-product-card {
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid transparent;
}
.pos-product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-color: #007bff;
}
.pos-product-card.out-of-stock {
    opacity: 0.6;
    cursor: not-allowed;
}
.pos-product-card.out-of-stock:hover {
    transform: none;
    box-shadow: none;
    border-color: transparent;
}
.cart-item-row {
    transition: background-color 0.2s ease;
}
.cart-item-row:hover {
    background-color: #f8f9fa;
}
.quantity-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
.quantity-btn {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
.total-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 15px;
    margin-top: 10px;
}
.barcode-scanner {
    position: relative;
}
.scanner-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}
.pos-shortcut {
    font-size: 0.8rem;
    color: #6c757d;
    margin-left: 5px;
}
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Shortcut Info -->
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="bx bx-info-circle me-2"></i>
        <strong>Shortcut:</strong> F1 = Fokus pencarian | F2 = Fokus barcode | F9 = Proses order | Esc = Clear cart
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bx bx-package me-2"></i>Produk</h5>
                    <div class="d-flex gap-2">
                        <div class="barcode-scanner">
                            <input id="barcode-input" class="form-control" placeholder="Scan/ketik barcode..." style="width: 200px;">
                            <i class="bx bx-qr-scan scanner-icon"></i>
                        </div>
                        <input id="product-search" class="form-control" placeholder="Cari produk..." style="width: 250px;">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" id="products-grid">
                        @foreach($products as $product)
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-3 product-card" data-name="{{ strtolower($product->name) }}" data-barcode="{{ $product->barcode ?? '' }}">
                            <div class="card h-100 pos-product-card {{ $product->stock <= 0 ? 'out-of-stock' : '' }}" 
                                 data-id="{{ $product->id }}" 
                                 data-name="{{ $product->name }}" 
                                 data-price="{{ $product->price }}" 
                                 data-stock="{{ $product->stock }}">
                                <div class="position-relative">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/img/avatars/default-product.png') }}" 
                                         class="card-img-top" style="height:120px; object-fit:cover;" alt="{{ $product->name }}">
                                    @if($product->stock <= 0)
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-danger">Habis</span>
                                        </div>
                                    @elseif($product->stock <= $product->min_stock)
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-warning">Stok Rendah</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column p-3">
                                    <h6 class="card-title mb-1 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                                    <p class="card-text mb-1 fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <p class="card-text mb-2 small">
                                        <i class="bx bx-package me-1"></i>Stok: <span class="product-stock fw-bold">{{ $product->stock }}</span>
                                    </p>
                                    @if($product->barcode)
                                        <p class="card-text mb-2 small text-muted">
                                            <i class="bx bx-qr me-1"></i>{{ $product->barcode }}
                                        </p>
                                    @endif
                                    <div class="mt-auto">
                                        @if($product->stock > 0)
                                            <button class="btn btn-primary btn-sm w-100 add-to-cart-btn">
                                                <i class="bx bx-plus me-1"></i>Tambah ke Keranjang
                                            </button>
                                        @else
                                            <button class="btn btn-secondary btn-sm w-100" disabled>
                                                <i class="bx bx-x me-1"></i>Stok Habis
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- No products found message -->
                    <div id="no-products" class="text-center py-5" style="display: none;">
                        <i class="bx bx-search-alt-2 display-1 text-muted"></i>
                        <h5 class="text-muted mt-3">Produk tidak ditemukan</h5>
                        <p class="text-muted">Coba kata kunci pencarian yang berbeda</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bx bx-cart me-2"></i>Keranjang <span id="cart-count" class="badge bg-primary ms-2">0</span></h5>
                    <button id="clear-cart" class="btn btn-outline-danger btn-sm" title="Clear Cart (Esc)">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div id="cart-items">
                        <div class="text-center py-4">
                            <i class="bx bx-cart-alt display-1 text-muted"></i>
                            <p class="text-muted mt-2">Keranjang masih kosong</p>
                            <small class="text-muted">Klik produk untuk menambahkan</small>
                        </div>
                    </div>

                    <hr>
                    <div class="mb-3">
                        <label for="customer" class="form-label"><i class="bx bx-user me-1"></i>Pelanggan</label>
                        <select id="customer" class="form-select">
                            <option value="">Walk-in Customer</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->phone ?? 'No Phone' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="bx bx-credit-card me-1"></i>Metode Pembayaran</label>
                        <div class="row">
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="payment_method" id="cash" value="cash" checked>
                                <label class="btn btn-outline-primary w-100" for="cash">
                                    <i class="bx bx-money d-block"></i>
                                    <small>Cash</small>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="payment_method" id="card" value="card">
                                <label class="btn btn-outline-primary w-100" for="card">
                                    <i class="bx bx-credit-card d-block"></i>
                                    <small>Card</small>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="payment_method" id="transfer" value="transfer">
                                <label class="btn btn-outline-primary w-100" for="transfer">
                                    <i class="bx bx-transfer d-block"></i>
                                    <small>Transfer</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label"><i class="bx bx-note me-1"></i>Catatan</label>
                        <textarea id="notes" class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea>
                    </div>

                    <!-- Payment Amount Input -->
                    <div class="mb-3" id="payment-section" style="display: none;">
                        <label for="payment-amount" class="form-label"><i class="bx bx-money me-1"></i>Jumlah Bayar</label>
                        <input type="number" id="payment-amount" class="form-control" placeholder="0" min="0">
                        <div id="change-amount" class="mt-2 text-success fw-bold" style="display: none;"></div>
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <button id="submit-order" class="btn btn-success btn-lg" disabled>
                            <i class="bx bx-check-circle me-2"></i>Proses Order <span class="pos-shortcut">(F9)</span>
                        </button>
                        <button id="preview-receipt" class="btn btn-outline-info" disabled>
                            <i class="bx bx-receipt me-2"></i>Preview Struk
                        </button>
                    </div>

                    <!-- Totals Section -->
                    <div class="total-section">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal" class="fw-bold">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Diskon:</span>
                            <span id="discount" class="fw-bold">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pajak:</span>
                            <span id="tax" class="fw-bold">Rp 0</span>
                        </div>
                        <hr class="my-2" style="border-color: rgba(255,255,255,0.3);">
                        <div class="d-flex justify-content-between">
                            <span class="h5 mb-0">TOTAL:</span>
                            <span id="total" class="h5 mb-0 fw-bold">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Preview Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Struk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="receipt-content" class="text-center">
                    <!-- Receipt content will be generated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">Print</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    (function(){
        const cart = {};
        const productStock = {};
        let products = [];

        function formatRupiah(n){
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(n);
        }

        function playSound(type) {
            // Simple beep sound using Web Audio API
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.value = type === 'success' ? 800 : 400;
            oscillator.type = 'sine';
            
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.1);
        }

        // Initialize product data
        document.querySelectorAll('.pos-product-card').forEach(card => {
            const id = card.dataset.id;
            const stock = parseInt(card.dataset.stock) || 0;
            productStock[id] = stock;
            
            products.push({
                id: id,
                name: card.dataset.name,
                price: parseFloat(card.dataset.price),
                stock: stock,
                barcode: card.closest('.product-card').dataset.barcode || ''
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F1') {
                e.preventDefault();
                document.getElementById('product-search').focus();
            } else if (e.key === 'F2') {
                e.preventDefault();
                document.getElementById('barcode-input').focus();
            } else if (e.key === 'F9') {
                e.preventDefault();
                document.getElementById('submit-order').click();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                clearCart();
            }
        });

        // Barcode scanner
        document.getElementById('barcode-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const barcode = this.value.trim();
                if (barcode) {
                    const product = products.find(p => p.barcode === barcode);
                    if (product) {
                        addToCart(product.id, product.name, product.price, product.stock);
                        this.value = '';
                        playSound('success');
                    } else {
                        playSound('error');
                        alert('Produk dengan barcode "' + barcode + '" tidak ditemukan');
                    }
                }
            }
        });

        // Product search with debouncing
        let searchTimeout;
        document.getElementById('product-search').addEventListener('input', function(){
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const q = this.value.trim().toLowerCase();
                let visibleCount = 0;
                
                document.querySelectorAll('.product-card').forEach(card => {
                    const name = card.dataset.name || '';
                    const isVisible = name.includes(q);
                    card.style.display = isVisible ? '' : 'none';
                    if (isVisible) visibleCount++;
                });
                
                document.getElementById('no-products').style.display = visibleCount === 0 ? 'block' : 'none';
            }, 300);
        });

        // Product click to add to cart
        document.querySelectorAll('.pos-product-card').forEach(card => {
            card.addEventListener('click', function() {
                if (!this.classList.contains('out-of-stock')) {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const price = parseFloat(this.dataset.price);
                    const stock = parseInt(this.dataset.stock);
                    addToCart(id, name, price, stock);
                }
            });
        });

        function addToCart(id, name, price, stock) {
            if (stock <= 0) {
                playSound('error');
                alert('Stok produk habis');
                return;
            }
            
            if (cart[id]) {
                if (cart[id].qty < stock) {
                    cart[id].qty += 1;
                    playSound('success');
                } else {
                    playSound('error');
                    alert('Stok tidak mencukupi');
                }
            } else {
                cart[id] = { name: name, price: price, qty: 1, stock: stock };
                playSound('success');
            }
            renderCart();
        }

        function updateCartCount() {
            const count = Object.keys(cart).length;
            document.getElementById('cart-count').textContent = count;
        }

        function recalcTotals(){
            let subtotal = 0;
            Object.keys(cart).forEach(id => {
                subtotal += cart[id].price * cart[id].qty;
            });
            const discount = 0; // placeholder
            const tax = 0; // placeholder
            const total = subtotal + tax - discount;

            document.getElementById('subtotal').textContent = formatRupiah(subtotal);
            document.getElementById('discount').textContent = formatRupiah(discount);
            document.getElementById('tax').textContent = formatRupiah(tax);
            document.getElementById('total').textContent = formatRupiah(total);
            
            // Enable/disable buttons
            const hasItems = Object.keys(cart).length > 0;
            document.getElementById('submit-order').disabled = !hasItems;
            document.getElementById('preview-receipt').disabled = !hasItems;
            
            // Show payment section for cash
            const isCash = document.querySelector('input[name="payment_method"]:checked').value === 'cash';
            document.getElementById('payment-section').style.display = isCash && hasItems ? 'block' : 'none';
            
            // Calculate change
            if (isCash && hasItems) {
                const paymentAmount = parseFloat(document.getElementById('payment-amount').value) || 0;
                const change = paymentAmount - total;
                const changeDiv = document.getElementById('change-amount');
                if (paymentAmount > 0) {
                    changeDiv.style.display = 'block';
                    changeDiv.innerHTML = change >= 0 ? 
                        `<i class="bx bx-check-circle me-1"></i>Kembalian: ${formatRupiah(change)}` :
                        `<i class="bx bx-x-circle me-1"></i>Kurang: ${formatRupiah(Math.abs(change))}`;
                    changeDiv.className = change >= 0 ? 'mt-2 text-success fw-bold' : 'mt-2 text-danger fw-bold';
                } else {
                    changeDiv.style.display = 'none';
                }
            }
        }

        function renderCart(){
            const container = document.getElementById('cart-items');
            container.innerHTML = '';
            const keys = Object.keys(cart);
            
            if(keys.length === 0){
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="bx bx-cart-alt display-1 text-muted"></i>
                        <p class="text-muted mt-2">Keranjang masih kosong</p>
                        <small class="text-muted">Klik produk untuk menambahkan</small>
                    </div>`;
                updateCartCount();
                recalcTotals();
                return;
            }

            const table = document.createElement('table');
            table.className = 'table table-sm';
            table.innerHTML = '<thead><tr><th>Item</th><th class="text-center">Qty</th><th class="text-end">Total</th><th></th></tr></thead>';
            const tbody = document.createElement('tbody');
            
            keys.forEach(id => {
                const item = cart[id];
                const tr = document.createElement('tr');
                tr.className = 'cart-item-row';

                const qtyControls = `
                    <div class="quantity-controls">
                        <button class="btn btn-outline-danger btn-sm quantity-btn btn-decrease" data-id="${id}">
                            <i class="bx bx-minus"></i>
                        </button>
                        <input type="number" min="1" max="${item.stock}" class="form-control form-control-sm text-center qty-input" 
                               value="${item.qty}" data-id="${id}" style="width:60px;" />
                        <button class="btn btn-outline-success btn-sm quantity-btn btn-increase" data-id="${id}">
                            <i class="bx bx-plus"></i>
                        </button>
                    </div>`;

                const subtotalItem = item.price * item.qty;
                tr.innerHTML = `
                    <td>
                        <div>
                            <div class="fw-bold">${item.name}</div>
                            <small class="text-muted">${formatRupiah(item.price)} x ${item.qty}</small>
                        </div>
                    </td>
                    <td class="text-center">${qtyControls}</td>
                    <td class="text-end fw-bold">${formatRupiah(subtotalItem)}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-danger remove-item" data-id="${id}" title="Remove item">
                            <i class="bx bx-trash"></i>
                        </button>
                    </td>`;
                tbody.appendChild(tr);
            });
            
            table.appendChild(tbody);
            container.appendChild(table);

            // Attach event handlers
            attachCartEventHandlers();
            updateCartCount();
            recalcTotals();
        }

        function attachCartEventHandlers() {
            // Remove item
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', function(){
                    const id = this.dataset.id;
                    delete cart[id];
                    renderCart();
                });
            });

            // Decrease quantity
            document.querySelectorAll('.btn-decrease').forEach(btn => {
                btn.addEventListener('click', function(){
                    const id = this.dataset.id;
                    if(!cart[id]) return;
                    if(cart[id].qty > 1) {
                        cart[id].qty--;
                        renderCart();
                    }
                });
            });

            // Increase quantity
            document.querySelectorAll('.btn-increase').forEach(btn => {
                btn.addEventListener('click', function(){
                    const id = this.dataset.id;
                    if(!cart[id]) return;
                    const stock = cart[id].stock;
                    if(cart[id].qty < stock) {
                        cart[id].qty++;
                        renderCart();
                    } else {
                        playSound('error');
                        alert('Stok tidak mencukupi');
                    }
                });
            });

            // Direct quantity input
            document.querySelectorAll('.qty-input').forEach(input => {
                input.addEventListener('change', function(){
                    const id = this.dataset.id;
                    let v = parseInt(this.value) || 1;
                    const stock = cart[id].stock;
                    if(v < 1) v = 1;
                    if(v > stock) {
                        playSound('error');
                        alert('Melebihi stok tersedia');
                        v = stock;
                    }
                    cart[id].qty = v;
                    renderCart();
                });
            });
        }

        // Clear cart
        function clearCart() {
            if (Object.keys(cart).length > 0 && confirm('Hapus semua item dari keranjang?')) {
                Object.keys(cart).forEach(id => delete cart[id]);
                renderCart();
            }
        }

        document.getElementById('clear-cart').addEventListener('click', clearCart);

        // Payment method change
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', recalcTotals);
        });

        // Payment amount input
        document.getElementById('payment-amount').addEventListener('input', recalcTotals);

        // Preview receipt
        document.getElementById('preview-receipt').addEventListener('click', function() {
            generateReceiptPreview();
            new bootstrap.Modal(document.getElementById('receiptModal')).show();
        });

        function generateReceiptPreview() {
            const customer = document.getElementById('customer');
            const customerName = customer.options[customer.selectedIndex].text;
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const notes = document.getElementById('notes').value;
            
            let subtotal = 0;
            Object.keys(cart).forEach(id => {
                subtotal += cart[id].price * cart[id].qty;
            });
            
            let receiptHTML = `
                <div style="max-width: 300px; margin: 0 auto; font-family: monospace; font-size: 12px;">
                    <div style="text-align: center; border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px;">
                        <h4>TOKO ABC</h4>
                        <p>Jl. Contoh No. 123<br>Telp: 021-12345678</p>
                    </div>
                    
                    <div style="margin-bottom: 10px;">
                        <div>Tanggal: ${new Date().toLocaleString('id-ID')}</div>
                        <div>Kasir: ${document.querySelector('meta[name="user-name"]')?.content || 'Kasir'}</div>
                        <div>Customer: ${customerName}</div>
                    </div>
                    
                    <div style="border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px;">`;
            
            Object.keys(cart).forEach(id => {
                const item = cart[id];
                const total = item.price * item.qty;
                receiptHTML += `
                    <div style="display: flex; justify-content: space-between;">
                        <div>${item.name}</div>
                        <div>${formatRupiah(total)}</div>
                    </div>
                    <div style="margin-left: 10px; color: #666;">
                        ${item.qty} x ${formatRupiah(item.price)}
                    </div>`;
            });
            
            receiptHTML += `
                    </div>
                    
                    <div style="text-align: right;">
                        <div>Subtotal: ${formatRupiah(subtotal)}</div>
                        <div>Pajak: Rp 0</div>
                        <div>Diskon: Rp 0</div>
                        <div style="font-weight: bold; font-size: 14px; border-top: 1px solid #000; padding-top: 5px;">
                            TOTAL: ${formatRupiah(subtotal)}
                        </div>
                        <div style="margin-top: 10px;">
                            Bayar (${paymentMethod.toUpperCase()}): ${formatRupiah(subtotal)}
                        </div>
                    </div>
                    
                    ${notes ? `<div style="margin-top: 10px; font-style: italic;">Catatan: ${notes}</div>` : ''}
                    
                    <div style="text-align: center; margin-top: 20px; border-top: 1px dashed #000; padding-top: 10px;">
                        <p>Terima kasih atas kunjungan Anda!<br>Barang yang sudah dibeli tidak dapat dikembalikan</p>
                    </div>
                </div>`;
            
            document.getElementById('receipt-content').innerHTML = receiptHTML;
        }

        // Submit order
        document.getElementById('submit-order').addEventListener('click', function(){
            const items = Object.keys(cart).map(id => ({ product_id: id, quantity: cart[id].qty }));
            if(items.length === 0) { 
                alert('Keranjang kosong'); 
                return; 
            }

            // Client-side stock validation
            for(const id of Object.keys(cart)){
                const stock = cart[id].stock;
                if(cart[id].qty > stock) { 
                    alert(`Stok ${cart[id].name} tidak mencukupi.`); 
                    return; 
                }
            }

            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            // For cash payment, validate payment amount
            if (paymentMethod === 'cash') {
                const total = Object.keys(cart).reduce((sum, id) => sum + (cart[id].price * cart[id].qty), 0);
                const paymentAmount = parseFloat(document.getElementById('payment-amount').value) || 0;
                if (paymentAmount < total) {
                    alert('Jumlah pembayaran kurang dari total belanja');
                    return;
                }
            }

            const payload = {
                customer_id: document.getElementById('customer').value || null,
                items: items,
                payment_method: paymentMethod,
                notes: document.getElementById('notes').value || null
            };

            this.disabled = true;
            this.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>Memproses...';
            
            fetch('{{ route('cashier.pos.process-order') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            }).then(async r => {
                const data = await r.json().catch(()=>({ success:false, message:'Invalid response' }));
                if(r.ok && data.success){
                    playSound('success');
                    // Clear cart and redirect
                    Object.keys(cart).forEach(id => delete cart[id]);
                    renderCart();
                    window.location.href = '/cashier/orders/' + data.order_id;
                } else {
                    playSound('error');
                    alert(data.message || 'Gagal memproses order');
                }
            }).catch(err => {
                console.error(err);
                playSound('error');
                alert('Terjadi kesalahan saat memproses order');
            }).finally(() => { 
                this.disabled = false; 
                this.innerHTML = '<i class="bx bx-check-circle me-2"></i>Proses Order <span class="pos-shortcut">(F9)</span>';
            });
        });

        // Initialize
        renderCart();
        
    })();
</script>
@endpush
