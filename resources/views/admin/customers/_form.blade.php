@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="mb-3 col-md-6">
        <label for="name" class="form-label">Nama Lengkap</label>
        <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $customer->name ?? '') }}" required autofocus />
    </div>
    <div class="mb-3 col-md-6">
        <label for="email" class="form-label">E-mail</label>
        <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $customer->email ?? '') }}" placeholder="contoh@email.com" />
    </div>
    <div class="mb-3 col-md-6">
        <label for="phone" class="form-label">Nomor Telepon</label>
        <input class="form-control" type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" />
    </div>
    <div class="mb-3 col-12">
        <label for="address" class="form-label">Alamat</label>
        <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $customer->address ?? '') }}</textarea>
    </div>
</div>