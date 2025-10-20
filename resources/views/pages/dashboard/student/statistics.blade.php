@extends('layouts.dashboard')
@section('title', 'Statistik - Ambalance')
@section('meta-description', 'Statistik tabungan siswa di Ambalance')
@section('meta-keywords', 'Statistik, Dashboard, Monitoring Tabungan, Tabungan Siswa, Siswa Sekolah')
@section('content')
    <div class="row mb-3 mb-md-4">
        <div class="col">
            <h3 class="fw-bold mb-0">Statistik Tabungan</h3>
            <p class="text-muted mb-0">Data ringkasan aktivitas tabungan kamu di Ambalance.</p>
        </div>
    </div>
    <div class="row g-3 align-items-stretch mb-3 mb-md-4" id="statistics-summary">
        <div class="col-lg-4 col-md-6">
            <div class="card bg-secondary-dark dashnum-card text-white overflow-hidden mb-0">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-arrow-up"></i>
                            </div>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2" id="totalIn">
                        <i class="ti ti-arrow-up-right-circle opacity-50"></i>
                    </span>
                    <p class="mb-0 opacity-50">Total Transaksi masuk</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card bg-primary-dark dashnum-card text-white overflow-hidden mb-0">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-arrow-down"></i>
                            </div>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2" id="totalOut">
                        <i class="ti ti-arrow-up-right-circle opacity-50"></i>
                    </span>
                    <p class="mb-0 opacity-50">Total Transaksi Keluar</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 h-100">
            <div class="row">
                <div class="col-12">
                    <div
                        class="card bg-primary-dark dashnum-card dashnum-card-small text-white overflow-hidden mb-3 mb-md-4">
                        <span class="round bg-primary small"></span>
                        <span class="round bg-primary big"></span>
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="avtar avtar-lg">
                                    <i class="text-white ti ti-credit-card"></i>
                                </div>
                                <div class="ms-2">
                                    <h4 id="valueIn" class="text-white mb-1">-</h4>
                                    <p class="mb-0 opacity-75 text-sm">Saldo Masuk</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card dashnum-card dashnum-card-small text-white overflow-hidden mb-0">
                        <span class="round bg-warning small"></span>
                        <span class="round bg-warning big"></span>
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="avtar avtar-lg bg-light-warning">
                                    <i class="text-warning ti ti-credit-card"></i>
                                </div>
                                <div class="ms-2">
                                    <h4 id="valueOut" class="mb-1 text-dark">-</h4>
                                    <p class="mb-0 opacity-75 text-sm text-dark">Saldo Keluar</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <h5 class="fw-semibold mb-3">Pertumbuhan Mingguan</h5>
            <canvas id="weeklyChart" height="150"></canvas>
        </div>
        <div class="col-lg-6">
            <h5 class="fw-semibold mb-3">Pertumbuhan Bulanan</h5>
            <canvas id="monthlyChart" height="150"></canvas>
        </div>
    </div>
    <script>
        async function getSavingsStatistics() {
            try {
                const response = await axios.get('/api/savings-statistics', {
                    headers: {
                        'Authorization': `Bearer ${getAuthToken()}`
                    }
                });
                const data = response.data.data;
                document.getElementById('totalIn').textContent = data.total_transactions.in;
                document.getElementById('totalOut').textContent = data.total_transactions.out;
                document.getElementById('valueIn').textContent = formatRupiah(data.total_value.in);
                document.getElementById('valueOut').textContent = formatRupiah(data.total_value.out);
                const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
                new Chart(weeklyCtx, {
                    type: 'line',
                    data: {
                        labels: data.growth.weekly.count.map((_, i) => `Minggu ${i + 1}`),
                        datasets: [{
                                label: 'Jumlah Transaksi',
                                data: data.growth.weekly.count,
                                borderColor: '#36a2eb',
                                backgroundColor: 'rgba(54,162,235,0.2)',
                                yAxisID: 'y1',
                                tension: 0.3,
                                fill: false,
                            },
                            {
                                label: 'Jumlah Pembayaran (Rp)',
                                data: data.growth.weekly.amount,
                                borderColor: '#4caf50',
                                backgroundColor: 'rgba(76,175,80,0.2)',
                                yAxisID: 'y2',
                                tension: 0.3,
                                fill: false,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        stacked: false,
                        scales: {
                            y1: {
                                type: 'linear',
                                position: 'left',
                                suggestedMin: 0,
                                title: {
                                    display: true,
                                    text: 'Jumlah Transaksi'
                                },
                                ticks: {
                                    color: '#36a2eb'
                                },
                                grace: '10%',
                            },
                            y2: {
                                type: 'linear',
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Jumlah Pembayaran (Rp)'
                                },
                                grid: {
                                    drawOnChartArea: false
                                },
                                ticks: {
                                    color: '#4caf50'
                                },
                                grace: '10%',
                            }
                        }
                    }
                });
                const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
                new Chart(monthlyCtx, {
                    type: 'bar',
                    data: {
                        labels: data.growth.monthly.count.map((_, i) => `Bulan ${i + 1}`),
                        datasets: [{
                                label: 'Jumlah Transaksi',
                                data: data.growth.monthly.count,
                                backgroundColor: 'rgba(54,162,235,0.5)',
                                yAxisID: 'y1'
                            },
                            {
                                label: 'Jumlah Pembayaran (Rp)',
                                data: data.growth.monthly.amount,
                                backgroundColor: 'rgba(76,175,80,0.5)',
                                yAxisID: 'y2'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        scales: {
                            y1: {
                                type: 'linear',
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Jumlah Transaksi'
                                },
                                ticks: {
                                    color: '#36a2eb'
                                },
                                grace: '10%',
                            },
                            y2: {
                                type: 'linear',
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Jumlah Pembayaran (Rp)'
                                },
                                grid: {
                                    drawOnChartArea: false
                                },
                                ticks: {
                                    color: '#4caf50'
                                },
                                grace: '10%',
                            }
                        }
                    }
                });
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
        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(value);
        }
        document.addEventListener('DOMContentLoaded', async () => {
            await getSavingsStatistics();
        });
    </script>
@endsection
