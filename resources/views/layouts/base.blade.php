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
    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('static/css/bootstrap.min.css') }}">
    {{-- Bootstrap icons --}}
    <link rel="stylesheet" href="{{ asset('static/css/bootstrap-icons/bootstrap-icons.min.css') }}">
</head>
<body>
    {{-- Axios --}}
    <script src="{{ asset('static/js/axios.min.js') }}"></script>
    {{-- Navbar --}}
    <x-navbar></x-navbar>
    {{-- Content --}}
    <main>
        @yield('content')
    </main>
    {{-- Footer --}}
    <x-footer></x-footer>
    {{-- Bootstrap --}}
    <script src="{{ asset('static/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
