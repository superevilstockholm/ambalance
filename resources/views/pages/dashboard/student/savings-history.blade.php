@extends('layouts.dashboard')
@section('title', 'Riwayat Tabungan - Ambalance')
@section('meta-description', 'Riwayat tabungan siswa di Ambalance')
@section('meta-keywords',
    'Riwayat, Riwayat Tabungan, Log Tabungan, Dashboard, Monitoring Tabungan, Tabungan Siswa,
    Siswa Sekolah')
@section('content')
    <div class="row mb-3">
        <div class="col-12 mb-2 mb-md-3">
            <h3 class="fw-bold d-flex align-items-center gap-2 fs-3">
                <i class="ti ti-history"></i>
                Daftar Riwayat Tabungan
            </h3>
        </div>
        <div class="col-12 mb-2 mb-md-3">
            <form method="GET" id="form-search" class="d-flex flex-column flex-md-row align-items-stretch gap-2 gap-md-3">
                <div class="form-floating flex-shrink-0">
                    <select name="type" id="type" class="form-select flex-grow-1 w-md-auto border-0 shadow-sm"
                        aria-label="Selected type">
                        <option selected value="">- Select -</option>
                        <option value="type">Type (In / Out)</option>
                        <option value="description">Description</option>
                        <option value="amount">Jumlah</option>
                        <option value="date">Tanggal</option>
                    </select>
                    <label for="type">Search By</label>
                </div>
                <div class="flex-grow-1" id="dynamicInputContainer">
                    <div class="form-floating w-100">
                        <input type="text" class="form-control border-0 shadow-sm bg-white" name="query" id="query"
                            placeholder="Cari riwayat tabungan" autocomplete="off">
                        <label class="text-muted" for="query">Search</label>
                    </div>
                </div>
                <button class="btn btn-primary border-0 shadow-sm" type="submit"><i class="ti ti-search"></i></button>
            </form>
        </div>
        <div class="col-12">
            <div class="form-floating w-auto">
                <select name="limit" id="limit" class="form-select flex-grow-1 w-md-auto border-0 shadow-sm"
                    aria-label="Limit">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <label for="limit">Limit</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card p-3 m-0 shadow-sm">
                <div class="card-body p-0 m-0">
                    <div id="historyContainer">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary"></div>
                            <p class="mt-2">Memuat data riwayat...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function generatePaginationLinks(currentPage, lastPage) {
            const links = [];
            const middleCount = 3;
            const sidePages = Math.floor(middleCount / 2);
            links.push({
                label: '&laquo; Sebelumnya',
                page: currentPage > 1 ? currentPage - 1 : null,
                url: currentPage > 1 ? '#' : null,
                active: false
            });
            if (lastPage <= middleCount + 4) {
                for (let i = 1; i <= lastPage; i++) {
                    links.push({
                        label: i.toString(),
                        page: i,
                        url: '#',
                        active: i === currentPage
                    });
                }
            } else {
                links.push({
                    label: '1',
                    page: 1,
                    url: '#',
                    active: currentPage === 1
                });
                let startPage = Math.max(2, currentPage - sidePages);
                let endPage = Math.min(lastPage - 1, currentPage + sidePages);
                if (currentPage <= 3) {
                    startPage = 2;
                    endPage = 4;
                }
                if (currentPage >= lastPage - 2) {
                    startPage = lastPage - 3;
                    endPage = lastPage - 1;
                }
                if (startPage > 2) {
                    links.push({
                        label: '...',
                        page: null,
                        url: null,
                        active: false
                    });
                }
                for (let i = startPage; i <= endPage; i++) {
                    links.push({
                        label: i.toString(),
                        page: i,
                        url: '#',
                        active: i === currentPage
                    });
                }
                if (endPage < lastPage - 1) {
                    links.push({
                        label: '...',
                        page: null,
                        url: null,
                        active: false
                    });
                }
                links.push({
                    label: lastPage.toString(),
                    page: lastPage,
                    url: '#',
                    active: currentPage === lastPage
                });
            }
            links.push({
                label: 'Berikutnya &raquo;',
                page: currentPage < lastPage ? currentPage + 1 : null,
                url: currentPage < lastPage ? '#' : null,
                active: false
            });
            return links;
        }
        async function getSavingsHistory(page = 1) {
            const container = document.getElementById('historyContainer');
            const form = document.getElementById('form-search');
            const limitElement = document.getElementById('limit');
            const limit = limitElement ? limitElement.value : 10;
            const typeSearch = document.getElementById('type').value;
            const params = new URLSearchParams();
            params.append('page', page);
            params.append('limit', limit);
            if (typeSearch === 'date') {
                const startDate = document.getElementById('start_date')?.value;
                const endDate = document.getElementById('end_date')?.value;
                if (startDate && endDate) {
                    params.append('type', 'date');
                    params.append('start_date', startDate);
                    params.append('end_date', endDate);
                }
            } else {
                const query = document.getElementById('query')?.value;
                if (query) {
                    params.append('query', query);
                }
                if (typeSearch) {
                    params.append('type', typeSearch);
                }
            }
            container.innerHTML = `
                <div class="text-center py-5">
                <div class="spinner-border text-primary"></div>
                <p class="mt-2">Memuat data riwayat...</p>
                </div>
            `;
            try {
                const response = await axios.get(`/api/savings-histories?${params.toString()}`, {
                    headers: {
                        'Authorization': `Bearer ${getAuthToken()}`
                    }
                });
                if (response.status === 200 && response.data.status === true) {
                    const {
                        data,
                        current_page,
                        last_page
                    } = response.data.data;
                    if (data.length === 0) {
                        container.innerHTML = `<p class="text-center text-muted py-5">Belum ada riwayat tabungan.</p>`;
                        return;
                    }
                    let rows = data.map((item, index) => {
                        const absoluteIndex = ((current_page - 1) * limit) + index + 1;
                        const amountClass = item.type === 'in' ? 'text-success text-opacity-75' : 'text-danger text-opacity-75';
                        const amountSign = item.type === 'in' ? '+' : '-';
                        const formattedAmount = parseInt(item.amount).toLocaleString('id-ID');
                        return `
                            <tr>
                                <td class="${amountClass} border-bottom">${absoluteIndex}</td>
                                <td class="${amountClass} border-bottom">${item.description}</td>
                                <td class="${amountClass} border-bottom">${item.type === 'in' ? 'Masuk' : 'Keluar'}</td>
                                <td class="${amountClass} border-bottom"><strong>${amountSign} Rp${formattedAmount}</strong></td>
                                <td class="${amountClass} border-bottom">${item.teacher ? item.teacher.name : '<em class="text-muted">Tidak diketahui</em>'}</td>
                                <td class="${amountClass} border-bottom">${new Date(item.created_at).toLocaleString('id-ID')}</td>
                            </tr>
                        `;
                    }).join('');
                    const links = generatePaginationLinks(current_page, last_page);
                    let pagination = links.map(link => `
                        <li class="page-item ${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${link.page ?? ''}">${link.label}</a>
                        </li>
                    `).join('');
                    container.innerHTML = `
                        <div class="table-responsive mb-2 mb-md-0">
                            <table class="table align-middle border-0">
                                <thead>
                                    <tr>
                                        <th class="border-bottom">#</th>
                                        <th class="border-bottom">Deskripsi</th>
                                        <th class="border-bottom">Type</th>
                                        <th class="border-bottom">Jumlah</th>
                                        <th class="border-bottom">Guru</th>
                                        <th class="border-bottom">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${rows}
                                </tbody>
                            </table>
                        </div>
                        <nav class="w-100 d-flex justify-content-center">
                            <div class="overflow-x-auto overflow-y-hidden py-0 px-2 mw-100">
                                <ul class="pagination flex-nowrap mb-0 mx-auto" style="width: max-content;">
                                    ${pagination}
                                </ul>
                            </div>
                        </nav>
                    `;
                    container.querySelectorAll('.page-link').forEach(btn => {
                        btn.addEventListener('click', e => {
                            e.preventDefault();
                            const newPage = btn.getAttribute('data-page');
                            if (newPage) getSavingsHistory(parseInt(newPage));
                        });
                    });
                } else {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat',
                        text: response.data.message || 'Terjadi kesalahan saat mengambil data.'
                    });
                }
            } catch (error) {
                console.error(error);
                await Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Tidak dapat memuat data riwayat tabungan.'
                });
            }
        }
        document.addEventListener('DOMContentLoaded', async () => {
            await getSavingsHistory();
            document.getElementById('form-search').addEventListener('submit', function(e) {
                e.preventDefault();
                getSavingsHistory(1);
            });
            document.getElementById('limit').addEventListener('change', function() {
                getSavingsHistory(1);
            });
        });
        const dynamicContainer = document.getElementById('dynamicInputContainer');
        const typeSelect = document.getElementById('type');
        typeSelect.addEventListener('change', function() {
            if (this.value === 'date') {
                dynamicContainer.innerHTML = `
                    <div class="d-flex flex-column flex-md-row gap-2 w-100">
                        <div class="form-floating flex-fill">
                            <input type="date" class="form-control border-0 shadow-sm bg-white" name="start_date" id="start_date">
                            <label for="start_date">Start Date</label>
                        </div>
                        <div class="form-floating flex-fill">
                            <input type="date" class="form-control border-0 shadow-sm bg-white" name="end_date" id="end_date">
                            <label for="end_date">End Date</label>
                        </div>
                    </div>
                `;
            } else {
                dynamicContainer.innerHTML = `
                    <div class="form-floating w-100">
                        <input type="text" class="form-control border-0 shadow-sm bg-white" name="query" id="query"
                            placeholder="Cari riwayat tabungan" autocomplete="off">
                        <label class="text-muted" for="query">Search</label>
                    </div>
                `;
            }
        });
    </script>
    <style>
        #query:hover {
            background-color: white !important;
        }
        .table td, .table th {
            white-space: nowrap;
            vertical-align: middle;
        }
    </style>
@endsection
