@extends('layout.main')

@section('title', 'Profil Saya')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Profil Saya</h5>
        </div>
        <div class="card-body">
                <form id="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4 text-center">
                        @php
                            use Illuminate\Support\Facades\Storage;
                            $hasAvatar = ($user->avatar && Storage::disk('public')->exists($user->avatar));
                            $avatarUrl = $hasAvatar ? asset('storage/' . $user->avatar) : asset('assets/img/avatars/default-user.png');
                        @endphp

                        <div class="position-relative d-inline-block">
                            {{-- Clean avatar area: show avatar img (or default) but present nicer placeholder when missing --}}
                            <img id="avatar-preview"
                                src="{{ $avatarUrl }}"
                                alt="avatar"
                                class="img-fluid rounded mb-2"
                                style="max-width:150px; height:150px; object-fit:cover;"
                                onerror="this.onerror=null;this.src='{{ asset('assets/img/avatars/default-user.png') }}';">

                            <div id="avatar-loading" class="spinner-border text-primary" role="status" aria-hidden="true" style="position:absolute; inset:0; margin:auto; width:3rem; height:3rem; display:none; opacity:0.95; background:rgba(255,255,255,0.6); border-radius:50%; align-items:center; justify-content:center;"></div>
                        </div>

                        {{-- Use a nicer button to trigger file select instead of raw input control --}}
                        <div class="mb-2 mt-2">
                            <input id="avatar-input" type="file" name="avatar" accept="image/*" class="d-none">
                            <label for="avatar-input" class="btn btn-outline-primary btn-sm">{{ $hasAvatar ? 'Ubah Avatar' : 'Unggah Avatar' }}</label>
                            <div class="small text-muted mt-2">PNG/JPG, maks 2MB</div>
                        </div>

                        <div class="text-muted">Role: <strong>{{ ucfirst($user->role) }}</strong></div>
                    </div>

                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password (kosongkan jika tidak ingin mengganti)</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const avatarInput = document.getElementById('avatar-input');
    const avatarPreview = document.getElementById('avatar-preview');
    const avatarLoading = document.getElementById('avatar-loading');
    const form = document.getElementById('profile-form');

    avatarInput && avatarInput.addEventListener('change', function(e){
        const file = this.files && this.files[0];
        if (!file) return;
        // show small local preview immediately
        const reader = new FileReader();
        reader.onload = function(ev){
            avatarPreview.src = ev.target.result;
        };
        reader.readAsDataURL(file);
    });

    // show a full-page loading overlay while the form is submitting
    form && form.addEventListener('submit', function(e){
        // show avatar inline loader to indicate upload started
        avatarLoading.style.display = 'flex';
        avatarLoading.style.alignItems = 'center';
        avatarLoading.style.justifyContent = 'center';

        // create global overlay
        const overlay = document.createElement('div');
        overlay.id = 'global-upload-overlay';
        overlay.style.position = 'fixed';
        overlay.style.inset = 0;
        overlay.style.background = 'rgba(0,0,0,0.45)';
        overlay.style.zIndex = 2050;
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.innerHTML = '<div class="text-white text-center"><div class="spinner-border text-light" role="status" style="width:3rem;height:3rem;"></div><div class="mt-2">Mengunggah...</div></div>';
        document.body.appendChild(overlay);

        // allow form to submit normally; overlay will remain until page reload/redirect
    });
});
</script>
@endpush
