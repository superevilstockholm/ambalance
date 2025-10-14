@extends('layouts.base')
@section('title', 'Daftar - Ambalance')
@section('meta-description', 'Daftar akun Ambalance kamu untuk melihat dashboard')
@section('meta-keywords', 'Auth, Register, Authentication, Daftar, Daftar Akun, Siswa, Guru, Student, Teacher')
@section('content')
{{-- Student Register --}}
<section class="vh-100 d-block" id="studentContainer">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-12 col-md-8 col-lg-5 py-3">
                <div class="card border-0 shadow py-4 p px-md-4">
                    <div class="card-body text-center">
                        <img style="max-height: 150px;" class="mb-4 w-100" src="{{ asset('static/images/vectors/undraw_welcome-cats.svg') }}" alt="Ambalance logo" fetchpriority="high">
                        <h2 class="fw-bold mb-2">Welcome Aboard</h2>
                        <p class="text-muted mb-4 fw-medium fs-09 px-md-2 px-lg-4">Silahkan daftar untuk membuat akun <a class="text-primary text-decoration-none fw-bold" href="{{ route('index') }}">Ambalance</a> kamu</p>
                        <form method="POST" id="studentForm">
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="text" name="nisn" id="nisn" placeholder="NISN" minlength="10" maxlength="10" autofocus required>
                                <label class="fs-09" for="nisn">NISN</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="date" name="studentDob" id="studentDob" placeholder="Date of Birth" required>
                                <label class="fs-09" for="studentDob">Date of Birth</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="email" name="studentEmail" id="studentEmail" placeholder="Email" required>
                                <label class="fs-09" for="studentEmail">Email</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="password" name="studentPassword" id="studentPassword" placeholder="Student Password" required>
                                <label class="fs-09" for="studentPassword">Password</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="password" name="studentPasswordConfirmation" id="studentPasswordConfirmation" placeholder="Student Password Confirmation" required>
                                <label class="fs-09" for="studentPasswordConfirmation">Password Confirmation</label>
                            </div>
                            <button class="btn btn-primary w-100 fs-09 mb-2" type="submit">Sign In</button>
                            <p class="text-muted fs-09 mb-1">Sudah punya akun? <a class="text-primary text-decoration-none fw-bold" href="{{ route('login') }}">Masuk disini</a></p>
                            <p class="text-muted fs-09 mb-0">Kamu seorang guru? <span style="user-select: none !important;" class="p-0 text-primary text-decoration-none fw-bold fs-09" type="button" id="studentButton">Daftar disini</span></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- Teacher Register --}}
<section class="vh-100 d-none" id="teacherContainer">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-12 col-md-8 col-lg-5 py-3">
                <div class="card border-0 shadow py-4 p px-md-4">
                    <div class="card-body text-center">
                        <img style="max-height: 150px;" loading="lazy" class="mb-4 w-100" src="{{ asset('static/images/vectors/undraw_welcome-cats.svg') }}" alt="Ambalance logo">
                        <h2 class="fw-bold mb-2">Welcome Aboard</h2>
                        <p class="text-muted mb-4 fw-medium fs-09 px-md-2 px-lg-4">Silahkan daftar untuk membuat akun <a class="text-primary text-decoration-none fw-bold" href="{{ route('index') }}">Ambalance</a> kamu</p>
                        <form method="POST" id="teacherForm">
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="text" name="nip" id="nip" placeholder="NIP" minlength="18" maxlength="18" required>
                                <label class="fs-09" for="nip">NIP</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="date" name="teacherDob" id="teacherDob" placeholder="Date of Birth" required>
                                <label class="fs-09" for="teacherDob">Date of Birth</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="email" name="teacherEmail" id="teacherEmail" placeholder="Email" required>
                                <label class="fs-09" for="teacherEmail">Email</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="password" name="teacherPassword" id="teacherPassword" placeholder="Teacher Password" required>
                                <label class="fs-09" for="teacherPassword">Password</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="password" name="teacherPasswordConfirmation" id="teacherPasswordConfirmation" placeholder="Teacher Password Confirmation" required>
                                <label class="fs-09" for="teacherPasswordConfirmation">Password Confirmation</label>
                            </div>
                            <button class="btn btn-primary w-100 fs-09 mb-2" type="submit">Sign In</button>
                            <p class="text-muted fs-09 mb-1">Sudah punya akun? <a class="text-primary text-decoration-none fw-bold" href="{{ route('login') }}">Masuk disini</a></p>
                            <p class="text-muted fs-09 mb-0">Kamu seorang guru? <span style="user-select: none !important;" class="p-0 text-primary text-decoration-none fw-bold fs-09" type="button" id="teacherButton">Daftar disini</span></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    async function register(type, identifier, dob, email, password, passwordConfirmation) {
        try {
            const payload = { password, email, dob };
            if (type === 'student') payload.nisn = identifier;
            else if (type === 'teacher') payload.nip = identifier;
            payload.dob = dob;
            payload.email = email;
            payload.password = password;
            payload.password_confirmation = passwordConfirmation;
            const response = await axios.post("/api/register", payload, {
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' }
            });
            if (response.status === 201 && response.data.status === true) {
                Swal.fire({
                    icon: 'success',
                    title: 'Registrasi Berhasil',
                    text: response.data.message,
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                }).then(() => window.location.href = "{{ route('login') }}");
                return;
            }
            Swal.fire({
                icon: 'error',
                title: 'Registrasi Gagal',
                text: response.data.message ?? 'Terjadi kesalahan!',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Registrasi Gagal',
                text: error.response?.data?.message ?? 'Terjadi kesalahan!',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        }
    }

    document.getElementById('studentForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const nisn = e.target.nisn.value;
        const dob = e.target.studentDob.value;
        const email = e.target.studentEmail.value;
        const password = e.target.studentPassword.value;
        const passwordConfirmation = e.target.studentPasswordConfirmation.value;
        await register('student', nisn, dob, email, password, passwordConfirmation);
    });

    document.getElementById('teacherForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const nip = e.target.nip.value;
        const dob = e.target.teacherDob.value;
        const email = e.target.teacherEmail.value;
        const password = e.target.teacherPassword.value;
        const passwordConfirmation = e.target.teacherPasswordConfirmation.value;
        await register('teacher', nip, dob, email, password, passwordConfirmation);
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
