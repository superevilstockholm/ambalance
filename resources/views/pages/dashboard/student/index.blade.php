@extends('layouts.dashboard')
@section('title', 'Dashboard Siswa - Ambalance')
@section('meta-description', 'Dashboard interaktif untuk memoitoring tabungan siswa')
@section('meta-keywords', 'Dashboard, Monitoring Tabungan, Tabungan Siswa, Siswa Sekolah')
@section('content')
    <div class="row mb-3 mb-md-4">
        <div class="col mb-0">
            {{-- Welcome Section --}}
            <div class="card shadow-sm mb-0 border-0 px-4">
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
    <div class="row g-3 mb-3 mb-md-4">
        <div class="col-12 col-sm-6 col-md-3 mb-0">
            <div class="card border-0 mb-0 shadow-sm rounded-3 text-center py-4">
                <i class="ti ti-user-check fs-2 text-primary mb-2"></i>
                <h5 class="fw-bold mb-0" id="savingsAmount">Rp. 0</h5>
                <small class="text-muted fs-09">Nominal Tabungan</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3 mb-0">
            <div class="card border-0 mb-0 shadow-sm rounded-3 text-center py-4">
                <i class="ti ti-arrow-up fs-2 text-success mb-2"></i>
                <h5 class="fw-bold mb-0" id="transactionsInCount">0</h5>
                <small class="text-muted fs-09">Transaksi Masuk</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3 mb-0">
            <div class="card border-0 mb-0 shadow-sm rounded-3 text-center py-4">
                <i class="ti ti-arrow-down fs-2 text-danger mb-2"></i>
                <h5 class="fw-bold mb-0" id="transactionsOutCount">0</h5>
                <small class="text-muted fs-09">Transaksi Keluar</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3 mb-0">
            <div class="card border-0 mb-0 shadow-sm rounded-3 text-center py-4">
                <i class="ti ti-file-text fs-2 text-warning mb-2"></i>
                <h5 class="fw-bold mb-0" id="transactionsCount">0</h5>
                <small class="text-muted fs-09">Total Transaksi</small>
            </div>
        </div>
    </div>
    {{-- Last Transactions --}}
    <div class="row g-3 mb-3 mb-md-4">
        <div class="col-12 col-md-6 mb-0">
            <div class="card border-0 shadow-sm mb-0 rounded-3 p-3 h-100">
                <h5 class="fw-semibold mb-3">Transaksi Masuk Terakhir</h5>
                <ul class="list-group list-group-flush text-muted" id="lastInTransactions">
                    <li class="list-group-item">Belum ada transaksi</li>
                </ul>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-0">
            <div class="card border-0 shadow-sm mb-0 rounded-3 p-3 h-100">
                <h5 class="fw-semibold mb-3">Transaksi Keluar Terakhir</h5>
                <ul class="list-group list-group-flush text-muted" id="lastOutTransactions">
                    <li class="list-group-item">Belum ada transaksi</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col mb-0">
            <div class="card border-0 mb-0 shadow-sm rounded-3">
                <div class="card-body d-flex flex-column gap-3">
                    <i class="ti ti-clipboard-text fs-1 text-primary"></i>
                    <div class="d-flex flex-column gap-2">
                        <h5 class="fw-bold mb-0">Petunjuk</h5>
                        <small class="text-muted">
                            <ul class="list-unstyled fs-09">
                                <li>1. Untuk melakukan pembayaran tabungan, silakan temui guru yang bertugas.</li>
                                <li>2. Guru akan memasukkan jumlah pembayaran ke sistem, yang otomatis tercatat di akun Anda.</li>
                                <li>3. Setelah pembayaran terinput, Anda dapat memeriksa saldo dan transaksi terbaru di dashboard ini.</li>
                                <li>4. Gunakan menu Statistik untuk melihat ringkasan tabungan dan menu Riwayat untuk melihat semua histori transaksi Anda.</li>
                                <li>5. Pastikan selalu memeriksa jumlah yang tercatat agar sesuai dengan pembayaran yang dilakukan.</li>
                            </ul>
                        </small>
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
                document.getElementById('userDashboardName').textContent = data.student.name;
                /* Tabungan */
                document.getElementById('savingsAmount').textContent = "Rp. " + data.savings.amount;
                document.getElementById('transactionsInCount').textContent = data.savings.total_in_transactions;
                document.getElementById('transactionsOutCount').textContent = data.savings.total_out_transactions;
                document.getElementById('transactionsCount').textContent = data.savings.total_in_transactions + data.savings.total_out_transactions;
                /* Last Transactions */
                const lastIn = document.getElementById('lastInTransactions');
                const lastOut = document.getElementById('lastOutTransactions');
                lastIn.innerHTML = '';
                lastOut.innerHTML = '';
                if (data.last_transactions.in.length > 0) {
                    data.last_transactions.in.forEach(tx => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex flex-column flex-md-row justify-content-md-between align-items-md-center';
                        li.innerHTML = `<span>${tx.description}</span><span class="text-success">Rp. ${parseFloat(tx.amount).toLocaleString('id-ID')}</span>`;
                        lastIn.appendChild(li);
                    });
                } else {
                    lastIn.innerHTML = `<li class="list-group-item text-muted">Belum ada transaksi</li>`;
                }
                if (data.last_transactions.out.length > 0) {
                    data.last_transactions.out.forEach(tx => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex flex-column flex-md-row justify-content-md-between align-items-md-center';
                        li.innerHTML = `<span>${tx.description}</span><span class="text-danger">Rp. ${parseFloat(tx.amount).toLocaleString('id-ID')}</span>`;
                        lastOut.appendChild(li);
                    });
                } else {
                    lastOut.innerHTML = `<li class="list-group-item text-muted">Belum ada transaksi</li>`;
                }
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
