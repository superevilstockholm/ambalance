@extends('layouts.base')
@section('title', 'Masuk Admin - Ambalance')
@section('meta-description', 'Masuk ke akun Admin Ambalance kamu untuk masuk ke menu dashboard')
@section('meta-keywords', 'Auth, Login, Authentication, Masuk, Masuk Akun, Admin, Administrator')
@section('content')
{{-- Student Login --}}
<section class="vh-100 d-block">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-12 col-md-8 col-lg-5 py-3">
                <div class="card border-0 py-4 p px-md-4">
                    <div class="card-body text-center">
                        <img style="max-height: 150px;" class="mb-4 w-100" src="{{ asset('static/images/vectors/undraw_savings.svg') }}" alt="Ambalance logo" fetchpriority="high">
                        <h2 class="fw-bold mb-2">Welcome Admin</h2>
                        <p class="text-muted mb-4 fw-medium fs-09 px-md-2 px-lg-4">Selamat datang kembali, silahkan masuk dengan akun <a class="text-primary text-decoration-none fw-bold" href="{{ route('index') }}">Ambalance</a> kamu</p>
                        <form method="POST" id="admin-form">
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="email" name="email" id="email" placeholder="Email" autofocus required>
                                <label class="fs-09" for="email">Email</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="password" name="password" id="password" placeholder="Password" required>
                                <label class="fs-09" for="password">Password</label>
                            </div>
                            <p class="text-end text-muted fs-09 mb-2">Lupa password? <a class="text-primary text-decoration-none fw-bold" href="#">Reset disini</a></p>
                            <button class="btn btn-primary w-100 fs-09 mb-2" type="submit">Log In</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    async function login(email, password) {
        try {
            const response = await axios.post("/api/admin-login", {
                email: email,
                password: password
            }, { headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' } });
            if (response.status == 200 && response.data.status == true) {
                Swal.fire({
                    icon: 'success',
                    title: 'Login Berhasil',
                    text: response.data.message,
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                }).then(() => window.location.href = "{{ route('admin.dashboard') }}");
                return;
            }
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: response.data.message ?? 'Terjadi kesalahan!',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: error.response?.data?.message ?? 'Terjadi kesalahan!',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        }
    }
    document.getElementById('admin-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = e.target.email.value;
        const password = e.target.password.value;
        await login(email, password);
    })
</script>
@endsection
