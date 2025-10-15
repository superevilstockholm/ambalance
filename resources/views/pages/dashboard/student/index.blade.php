@extends('layouts.dashboard')
@section('title', 'Dashboard Siswa - Ambalance')
@section('meta-description', 'Dashboard interaktif untuk memoitoring tabungan siswa')
@section('meta-keywords', 'Dashboard, Monitoring Tabungan, Tabungan Siswa, Siswa Sekolah')
@section('content')
    <div class="row mb-3">
        <div class="col">
            {{-- Welcome Section --}}
            <div class="card shadow-sm border-0 mb-4 px-4">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h4 class="fw-semibold mb-1">Selamat datang, <span class="text-primary" id="userDashboardName"></span>! 👋</h4>
                        <p class="text-muted mb-0">
                            Berikut adalah informasi tabungan kamu
                        </p>
                    </div>
                    <div class="d-none d-sm-block">
                        <img src="{{ asset('static/images/vectors/undraw_savings.svg') }}" alt="Dashboard" width="80" style="transform: scaleX(-1);" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Informasi Tabungan Section --}}
    <div class="row g-md-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 text-center py-4">
                <i class="ti ti-user-check fs-2 text-primary mb-2"></i>
                <h5 class="fw-bold mb-0" id="savingsAmount">Rp. 0</h5>
                <small class="text-muted">Nominal Tabungan</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 text-center py-4">
                <i class="ti ti-arrow-up fs-2 text-success mb-2"></i>
                <h5 class="fw-bold mb-0" id="transactionsInCount">0</h5>
                <small class="text-muted">Transaksi Masuk</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 text-center py-4">
                <i class="ti ti-arrow-down fs-2 text-danger mb-2"></i>
                <h5 class="fw-bold mb-0" id="transactionsOutCount">0</h5>
                <small class="text-muted">Transaksi Keluar</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 text-center py-4">
                <i class="ti ti-file-text fs-2 text-warning mb-2"></i>
                <h5 class="fw-bold mb-0" id="transactionsCount">0</h5>
                <small class="text-muted">Total Transaksi</small>
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

            document.getElementById('userDashboardName').textContent = data.student.name;

            /* Tabungan */
            document.getElementById('savingsAmount').textContent = "Rp. " + data.savings.amount;
            document.getElementById('transactionsInCount').textContent = data.savings.total_in_transactions;
            document.getElementById('transactionsOutCount').textContent = data.savings.total_out_transactions;
            document.getElementById('transactionsCount').textContent = data.savings.total_in_transactions + data.savings.total_out_transactions;
        } catch (e) {
            Swal.fire({
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
