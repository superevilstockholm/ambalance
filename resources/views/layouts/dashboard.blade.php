<!DOCTYPE html>
<html lang="id" class="h-100">
<head>
    {{-- Theme --}}
    <script>
        const theme = localStorage.getItem('theme');
        if (['light', 'dark'].includes(theme)) {
            document.querySelector('html').setAttribute('data-bs-theme', theme);
        } else {
            document.querySelector('html').setAttribute('data-bs-theme', 'light');
            localStorage.setItem('theme', 'light');
        }
    </script>
    {{-- SEO --}}
    <meta name="description" content="@yield('meta-description', 'Dashboard interaktif ambalance')">
    <meta name="keywords" content="@yield('meta-keywords', 'Ambalance, Kelola Tabungan, Monitoring Tabungan, Tabungan Siswa, Siswa Sekolah')">
    {{-- Meta --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard - Ambalance')</title>
    {{-- ====== Start Template ====== --}}
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="{{ asset('static/css/tabler-icons/tabler-icons.min.css') }}">
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('static/berry_template/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('static/berry_template/css/style-preset.css') }}">
    {{-- ====== End Template ====== --}}
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('static/css/style.css') }}">
</head>
<body class="h-100">
    {{-- Custom Script --}}
    <script>function getAuthToken() { const token = document.cookie.replace(/(?:(?:^|.*;\s*)auth_token\s*\=\s*([^;]*).*$)|^.*$/, '$1'); return token ? decodeURIComponent(token) : null; }</script>
    {{-- Axios --}}
    <script src="{{ asset('static/js/axios.min.js') }}" defer></script>
    {{-- Sweet Alert 2 --}}
    <script src="{{ asset('static/js/sweetalert2.min.js') }}" defer></script>
    {{-- Content --}}
    <x-sidebar :meta="$meta"></x-sidebar>
    <x-topbar></x-topbar>
    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>
    <footer class="pc-footer">
        <div class="footer-wrapper container">
            <div class="row justify-content-center">
                <div class="col-auto">
                    <p class="m-0 text-center">
                        Copyright &copy; 2025 AmbaToCode. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    {{-- Bootstrap JS --}}
    <script src="{{ asset('static/js/bootstrap.bundle.min.js') }}"></script>
    {{-- ====== Start Template ====== --}}
    {{-- Popper JS --}}
    <script src="{{ asset('static/berry_template/js/plugins/popper.min.js') }}"></script>
    {{-- Simple Bar JS --}}
    <script src="{{ asset('static/berry_template/js/plugins/simplebar.min.js') }}"></script>
    {{-- Feather Icons JS --}}
    <script src="{{ asset('static/berry_template/js/plugins/feather.min.js') }}"></script>
    {{-- Custom Template JS --}}
    <script src="{{ asset('static/berry_template/js/script.js') }}"></script>
    {{-- ====== End Template ====== --}}
</body>
</html>
