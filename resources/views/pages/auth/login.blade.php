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
                        <h2 class="fw-bold mb-2">{{ __('pages/auth/login.welcome_back') }}</h2>
                        <p class="text-muted mb-4 fw-medium fs-09 px-md-2 px-lg-4">
                            {!! __('pages/auth/login.student_message', ['webname_link' => '<a class="text-primary text-decoration-none fw-bold" href="' . route('index') . '">' . __('pages/auth/login.webname_message') . '</a>']) !!}
                        </p>
                        <form method="POST" id="studentForm">
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="text" name="nisn" id="nisn" placeholder="NISN" minlength="10" maxlength="10" autofocus required>
                                <label class="fs-09" for="nisn">{{ __('pages/auth/login.nisn') }}</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="password" name="studentPassword" id="studentPassword" placeholder="Student Password" required>
                                <label class="fs-09" for="studentPassword">{{ __('pages/auth/login.password') }}</label>
                            </div>
                            <p class="text-end text-muted fs-09 mb-2">
                                {!! __('pages/auth/login.forgot_password_message', ['reset_password_link' => '<a class="text-primary text-decoration-none fw-bold" href="javascript:void(0);">' . __('pages/auth/login.reset_password_message') . '</a>']) !!}
                            </p>
                            <button class="btn btn-primary w-100 fs-09 mb-2" type="submit">{{ __('pages/auth/login.login') }}</button>
                            <p class="text-muted fs-09 mb-1">
                                {!! __('pages/auth/login.dont_have_account_message', ['register_link' => '<a class="text-primary text-decoration-none fw-bold" href="' . route('register') . '">' . __('pages/auth/login.register_message') . '</a>']) !!}
                            </p>
                            <p class="text-muted fs-09 mb-1">
                                {!! __('pages/auth/login.you_are_a_teacher_message', ['teacher_login_button' => '<a class="text-primary text-decoration-none fw-bold" href="javascript:void(0);" id="studentButton">' . __('pages/auth/login.teacher_login_message') . '</a>']) !!}
                            </p>
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
                        <h2 class="fw-bold mb-2">{{ __('pages/auth/login.welcome_back') }}</h2>
                        <p class="text-muted mb-4 fw-medium fs-09 px-md-2 px-lg-4">
                            {!! __('pages/auth/login.teacher_message', ['webname_link' => '<a class="text-primary text-decoration-none fw-bold" href="' . route('index') . '">' . __('pages/auth/login.webname_message') . '</a>']) !!}
                        </p>
                        <form method="POST" id="teacherForm">
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="text" name="nip" id="nip" placeholder="NIP" minlength="18" maxlength="18" required>
                                <label class="fs-09" for="nip">{{ __('pages/auth/login.nip') }}</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fs-09" type="password" name="teacherPassword" id="teacherPassword" placeholder="Teacher Password" required>
                                <label class="fs-09" for="teacherPassword">{{ __('pages/auth/login.password') }}</label>
                            </div>
                            <p class="text-end text-muted fs-09 mb-2">
                                {!! __('pages/auth/login.forgot_password_message', ['reset_password_link' => '<a class="text-primary text-decoration-none fw-bold" href="javascript:void(0);">' . __('pages/auth/login.reset_password_message') . '</a>']) !!}
                            </p>
                            <button class="btn btn-primary w-100 fs-09 mb-2" type="submit">{{ __('pages/auth/login.login') }}</button>
                            <p class="text-muted fs-09 mb-1">
                                {!! __('pages/auth/login.dont_have_account_message', ['register_link' => '<a class="text-primary text-decoration-none fw-bold" href="' . route('register') . '">' . __('pages/auth/login.register_message') . '</a>']) !!}
                            </p>
                            <p class="text-muted fs-09 mb-0">
                                {!! __('pages/auth/login.you_are_a_student_message', ['student_login_button' => '<a class="text-primary text-decoration-none fw-bold" href="javascript:void(0);" id="teacherButton">' . __('pages/auth/login.teacher_login_message') . '</a>']) !!}
                            </p>
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
                await Swal.fire({
                    icon: 'success',
                    title: "{{ __('pages/auth/login.login_success') }}",
                    text: response.data.message,
                    showConfirmButton: false,
                    timer: 1000
                }).then(async () => {
                    window.location.href = `/${response.headers['x-user-role']}`;
                });
                return;
            }
            await Swal.fire({
                icon: 'error',
                title: "{{ __('pages/auth/login.login_failed') }}",
                text: response.data.message ?? "{{ __('pages/auth/login.error') }}",
                showConfirmButton: true,
                confirmButtonText: "{{ __('pages/auth/login.confirm_button') }}"
            });
        } catch (error) {
            await Swal.fire({
                icon: 'error',
                title: "{{ __('pages/auth/login.login_failed') }}",
                text: error.response?.data?.message ?? "{{ __('pages/auth/login.error') }}",
                showConfirmButton: true,
                confirmButtonText: "{{ __('pages/auth/login.confirm_button') }}"
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
