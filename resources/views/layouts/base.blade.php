<!DOCTYPE html>
<html lang="id">
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
    <meta name="description" content="@yield('meta-description', 'Ambalance platform kelola dan monitoring tabungan siswa')">
    <meta name="keywords" content="@yield('meta-keywords', 'Ambalance, Kelola Tabungan, Monitoring Tabungan, Tabungan Siswa, Siswa Sekolah')">
    {{-- Meta --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Ambalance - Kelola Tabungan')</title>
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('static/css/bootstrap.min.css') }}">
    {{-- Bootstrap icons --}}
    <link rel="stylesheet" href="{{ asset('static/css/bootstrap-icons/bootstrap-icons.min.css') }}">
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('static/css/style.css') }}">
</head>
<body>
    {{-- Axios --}}
    <script src="{{ asset('static/js/axios.min.js') }}" defer></script>
    {{-- Sweet Alert 2 --}}
    <script src="{{ asset('static/js/sweetalert2.min.js') }}" defer></script>
    {{-- Navbar --}}
    @if ($meta['showNavbar'] ?? true)
        <x-navbar></x-navbar>
    @endif
    {{-- Content --}}
    <main>
        @yield('content')
    </main>
    {{-- Footer --}}
    @if ($meta['showFooter'] ?? true)
        <x-footer></x-footer>
    @endif
    {{-- Bootstrap --}}
    <script src="{{ asset('static/js/bootstrap.bundle.min.js') }}" defer></script>
</body>
</html>
