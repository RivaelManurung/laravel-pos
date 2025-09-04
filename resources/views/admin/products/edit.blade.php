<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data"> {{-- Action diisi oleh JS --}}
                @csrf
                @method('PUT')
                <div class="modal-body">
                    {{-- Form partial akan diisi datanya oleh JS di halaman index --}}
                    @include('admin.products._form-edit') {{-- Menggunakan form partial terpisah untuk edit agar tidak konflik ID --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>