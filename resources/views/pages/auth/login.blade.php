@extends('layouts.base')
@section('title', 'Masuk - Ambalance')
@section('meta-description', 'Masuk ke akun Ambalance kamu untuk melihat dashboard')
@section('meta-keywords', 'Auth, Login, Authentication, Masuk, Masuk Akun, Siswa, Guru, Student, Teacher')
@section('content')
{{-- Student Login --}}
<section class="vh-100 d-block" id="studentContainer">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-12 col-md-8 col-lg-5 py-3">
                <div class="card border-0 py-4 p px-md-4">
                    <div class="card-body text-center">
                        <img style="max-height: 150px;" class="mb-4 w-100" src="{{ asset('static/images/vectors/undraw_savings.svg') }}" alt="Ambalance logo" fetchpriority="high">
                        <h2 class="fw-bold mb-2">Welcome Back</h2>
                        <p class="text-muted mb-4 fw-medium fs-09 px-md-2 px-lg-4">Selamat datang kembali siswa, silahkan masuk dengan akun <a class="text-primary text-decoration-none fw-bold" href="{{ route('index') }}">Ambalance</a> kamu</p>
                        <form method="POST" id="studentForm">
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="text" name="nisn" id="nisn" placeholder="NISN" minlength="10" maxlength="10" autofocus required>
                                <label class="fs-09" for="nisn">NISN</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="password" name="studentPassword" id="studentPassword" placeholder="Student Password" required>
                                <label class="fs-09" for="studentPassword">Password</label>
                            </div>
                            <p class="text-end text-muted fs-09 mb-2">Lupa password? <a class="text-primary text-decoration-none fw-bold" href="#">Reset disini</a></p>
                            <button class="btn btn-primary w-100 fs-09 mb-2" type="submit">Log In</button>
                            <p class="text-muted fs-09 mb-1">Belum punya akun? <a class="text-primary text-decoration-none fw-bold" href="{{ route('register') }}">Daftar disini</a></p>
                            <p class="text-muted fs-09 mb-0">Kamu seorang guru? <span style="user-select: none !important;" class="p-0 text-primary text-decoration-none fw-bold fs-09" type="button" id="studentButton">Masuk disini</span></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- Teacher Login --}}
<section class="vh-100 d-none" id="teacherContainer">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-12 col-md-8 col-lg-5 py-3">
                <div class="card border-0 py-4 p px-md-4">
                    <div class="card-body text-center">
                        <img style="max-height: 150px;" loading="lazy" class="mb-4 w-100" src="{{ asset('static/images/vectors/undraw_savings.svg') }}" alt="Ambalance logo">
                        <h2 class="fw-bold mb-2">Welcome Back</h2>
                        <p class="text-muted mb-4 fw-medium fs-09 px-md-2 px-lg-4">Selamat datang kembali guru, silahkan masuk dengan akun <a class="text-primary text-decoration-none fw-bold" href="{{ route('index') }}">Ambalance</a> kamu</p>
                        <form method="POST" id="teacherForm">
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="text" name="nip" id="nip" placeholder="NIP" minlength="18" maxlength="18" required>
                                <label class="fs-09" for="nip">NIP</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="password" name="teacherPassword" id="teacherPassword" placeholder="Teacher Password" required>
                                <label class="fs-09" for="teacherPassword">Password</label>
                            </div>
                            <p class="text-end text-muted fs-09 mb-2">Lupa password? <a class="text-primary text-decoration-none fw-bold" href="#">Reset disini</a></p>
                            <button class="btn btn-primary w-100 fs-09 mb-2" type="submit">Log In</button>
                            <p class="text-muted fs-09 mb-1">Belum punya akun? <a class="text-primary text-decoration-none fw-bold" href="{{ route('register') }}">Daftar disini</a></p>
                            <p class="text-muted fs-09 mb-0">Kamu seorang siswa? <span style="user-select: none !important;" class="p-0 text-primary text-decoration-none fw-bold fs-09" type="button" id="teacherButton">Masuk disini</span></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    async function login(type, identifier, password) {
        try {
            const payload = {};
            if (type === 'student') payload.nisn = identifier;
            else if (type === 'teacher') payload.nip = identifier;
            payload.password = password;
            const response = await axios.post("/api/login", payload, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            if (response.status === 200 && response.data.status === true) {
                window.location.href = `/${response.headers['x-user-role']}`;
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

    document.getElementById('studentForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const nisn = e.target.nisn.value;
        const password = e.target.studentPassword.value;
        await login('student', nisn, password);
    });
    document.getElementById('teacherForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const nip = e.target.nip.value;
        const password = e.target.teacherPassword.value;
        await login('teacher', nip, password);
    });

    document.addEventListener('DOMContentLoaded', () => {
        /* Buttons */
        const studentButton = document.getElementById('studentButton');
        const teacherButton = document.getElementById('teacherButton');
        /* Containers */
        const studentContainer = document.getElementById('studentContainer');
        const teacherContainer = document.getElementById('teacherContainer');

        function showStudent() {
            studentContainer.classList.remove('d-none');
            teacherContainer.classList.add('d-none');
        }

        function showTeacher() {
            studentContainer.classList.add('d-none');
            teacherContainer.classList.remove('d-none');
        }

        studentButton.addEventListener('click', showTeacher);
        teacherButton.addEventListener('click', showStudent);
    });
</script>
@endsection
