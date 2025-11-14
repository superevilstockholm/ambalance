@extends('layouts.dashboard')
@section('title', 'Dashboard Guru - Ambalance')
@if (App::isLocale('en'))
    {{-- English --}}
    @section('meta-description', 'Teacher Dashboard Overview: Get a quick summary of your class data, student list, and managed student savings balances in Ambalance.')
    @section('meta-keywords', 'teacher dashboard, class overview, student data, student savings balance, Ambalance, teacher portal')
@else
    {{-- Default ID --}}
    @section('meta-description', 'Selamat datang di Dashboard Guru Ambalance. Dapatkan ringkasan cepat data kelas, jumlah siswa, dan total saldo tabungan siswa yang Anda kelola.')
    @section('meta-keywords', 'dashboard guru, ringkasan data kelas, data siswa, saldo tabungan, ambalance, portal guru')
@endif
@section('content')
    <div class="row mb-3 mb-md-4">
        <div class="col mb-0">
            {{-- Welcome Section --}}
            <div class="card shadow-sm mb-0 border-0 px-4">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h3 class="fw-semibold mb-1 fs-3">Selamat datang, <span class="text-primary" id="userDashboardName"></span>! 👋</h3>
                        <p class="text-muted mb-0">
                            Berikut adalah informasi tabungan para siswa yang Anda kelola.
                        </p>
                    </div>
                    <div class="d-none d-sm-block">
                        <img src="{{ asset('static/images/vectors/undraw_savings.svg') }}" alt="Dashboard" width="80" style="transform: scaleX(-1);" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        async function getDashboardData() {
            try {
                const response = await axios.get('/api/dashboard-data', {
                    headers: { 'Authorization': `Bearer ${getAuthToken()}` }
                });
                const data = response.data.data;
                document.getElementById('userDashboardName').textContent = data.teacher.name;
            } catch (e) {
                await Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: e.response?.data?.message ?? 'Terjadi kesalahan saat mengambil data!',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
            }
        }
        document.addEventListener('DOMContentLoaded', async () => {
            await getDashboardData();
        });
    </script>
@endsection
