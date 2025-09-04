<!DOCTYPE html>
<html lang="id" class="light-style" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets/') }}"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>404 Error - Halaman Tidak Ditemukan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}" />
</head>

<body>
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            <h2 class="mb-2 mx-2">Halaman Tidak Ditemukan :(</h2>
            <p class="mb-4 mx-2">Oops! 😖 URL yang diminta tidak ditemukan di server ini.</p>

            {{-- PERBAIKAN: Tombol kembali dibuat dinamis sesuai status login & peran --}}
            {{-- @auth
            @if (in_array(Auth::user()->peran, ['admin']))
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
            @else
            <a href="{{ route('user.peminjaman.index') }}" class="btn btn-primary">Kembali ke Beranda</a>
            @endif
            @else
            <a href="{{ url('/') }}" class="btn btn-primary">Kembali ke Halaman Utama</a>
            @endguest --}}

            <div class="mt-3">
                <img src="{{ asset('assets/img/illustrations/page-misc-error-light.png') }}" alt="page-misc-error-light"
                    width="500" class="img-fluid" data-app-dark-img="illustrations/page-misc-error-dark.png"
                    data-app-light-img="illustrations/page-misc-error-light.png" />
            </div>
        </div>
    </div>
</body>

</html>