@extends('layouts.base')
@section('title', 'Masuk - Ambalance')
@section('meta-description', 'Masuk ke akun Ambalance kamu untuk melihat dashboard')
@section('meta-keywords', 'Auth, Login, Authentication, Masuk, Masuk Akun, Siswa, Guru, Student, Teacher')
@section('content')
{{-- Student Login --}}
<section class="vh-100 d-block" id="studentForm">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-12 col-md-8 col-lg-5 py-3">
                <div class="card border-0 shadow py-4 p x-md-4">
                    <div class="card-body text-center">
                        <img style="height: 150px;" class="mb-4" src="{{ asset('static/images/vectors/undraw_savings.svg') }}" alt="Ambalance logo">
                        <h2 class="fw-bold mb-2">Welcome Back</h2>
                        <p class="text-muted mb-4 fw-medium fs-09 px-md-4">Selamat datang kembali siswa, silahkan masuk dengan akun <a class="text-primary text-decoration-none fw-bold" href="{{ route('index') }}">Ambalance</a> kamu</p>
                        <form method="POST">
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="text" name="nisn" id="nisn" placeholder="NISN" min="10" max="10" autofocus required>
                                <label class="fs-09" for="nisn">NISN</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="password" name="studentPassword" id="studentPassword" placeholder="Student Password" required>
                                <label class="fs-09" for="studentPassword">Password</label>
                            </div>
                            <p class="text-end text-muted fs-09 mb-2">Lupa password? <a class="text-primary text-decoration-none fw-bold" href="#">Reset disini</a></p>
                            <button class="btn btn-primary w-100 fs-09 mb-2" type="submit">Log In</button>
                            <p class="text-muted fs-09 mb-1">Belum punya akun? <a class="text-primary text-decoration-none fw-bold" href="{{ route('register') }}">Daftar disini</a></p>
                            <p class="text-muted fs-09 mb-0">Kamu seorang guru? <button class="btn p-0 text-primary text-decoration-none fw-bold fs-09" type="button" id="studentButton">Masuk disini</button></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- Teacher Login --}}
<section class="vh-100 d-none" id="teacherForm">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-12 col-md-8 col-lg-5 py-3">
                <div class="card border-0 shadow py-4 p x-md-4">
                    <div class="card-body text-center">
                        <img style="height: 150px;" class="mb-4" src="{{ asset('static/images/vectors/undraw_savings.svg') }}" alt="Ambalance logo">
                        <h2 class="fw-bold mb-2">Welcome Back</h2>
                        <p class="text-muted mb-4 fw-medium fs-09 px-md-4">Selamat datang kembali guru, silahkan masuk dengan akun <a class="text-primary text-decoration-none fw-bold" href="{{ route('index') }}">Ambalance</a> kamu</p>
                        <form method="POST">
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
                            <p class="text-muted fs-09 mb-0">Kamu seorang siswa? <button class="btn p-0 text-primary text-decoration-none fw-bold fs-09" type="button" id="teacherButton">Masuk disini</button></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        /* Buttons */
        const studentButton = document.getElementById('studentButton');
        const teacherButton = document.getElementById('teacherButton');
        /* Forms */
        const studentForm = document.getElementById('studentForm');
        const teacherForm = document.getElementById('teacherForm');

        function showStudent() {
            console.log("Student Clicked");
            studentForm.classList.remove('d-none');
            teacherForm.classList.add('d-none');
        }

        function showTeacher() {
            console.log("Teacher Clicked");
            studentForm.classList.add('d-none');
            teacherForm.classList.remove('d-none');
        }

        studentButton.addEventListener('click', showTeacher);
        teacherButton.addEventListener('click', showStudent);
    });
</script>
@endsection
