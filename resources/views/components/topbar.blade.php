<header class="pc-header">
    <div class="header-wrapper">
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                {{-- Sidebar Collapse --}}
                <li class="pc-h-item header-mobile-collapse">
                    <a href="#" class="pc-head-link head-link-secondary ms-0" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link head-link-secondary ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">
                {{-- Notification --}}
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link head-link-secondary dropdown-toggle arrow-none me-0"
                        data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                        aria-expanded="false">
                        <i class="ti ti-bell"></i>
                    </a>
                    <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header">
                            <h5 class="d-flex align-items-center justify-content-between">
                                Recent Notification
                                <span id="readAllNotifications"
                                    class="badge bg-primary rounded-pill d-flex align-items-center gap-1"
                                    style="cursor: pointer !important;">
                                    <i class="ti ti-bell-check"></i>
                                    Read All
                                </span>
                            </h5>
                        </div>
                        <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative"
                            style="max-height: calc(100vh - 215px)">
                            <div id="notificationList" class="list-group list-group-flush w-100">
                                {{-- Notifications --}}
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="text-center py-2">
                            <a href="#" class="link-primary">Show All Notifications</a>
                        </div>
                    </div>
                </li>
                {{-- User Profile --}}
                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link head-link-primary dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img id="userProfilePicture" src="{{ asset('static/images/default_profile.svg') }}"
                            alt="user-image" class="user-avtar object-fit-cover" style="width: 34px; height: 34px;" />
                        <span>
                            <i class="ti ti-settings"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header">
                            <h4 class="d-flex align-items-center gap-1">
                                <span><span id="greeting"></span>,</span>
                                <span class="small text-muted text-truncate d-inline-block fs-09"
                                    style="max-width: 120px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"
                                    id="userName">User</span>
                            </h4>
                            <p class="text-muted fs-09" id="userRole">Unknown</p>
                            <hr />
                            <div class="profile-notification-scroll position-relative"
                                style="max-height: calc(100vh - 280px)">
                                <a href="#" id="account-profile-button" class="dropdown-item">
                                    <i class="ti ti-user"></i>
                                    <span>Account Profile</span>
                                </a>
                                <a id="logout-button" href="#" class="dropdown-item">
                                    <i class="ti ti-logout"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
<!-- Modal Account Settings -->
<div class="modal fade" id="accountProfileModal" tabindex="-1" aria-labelledby="accountProfileLabel"
    aria-hidden="true" data-bs-focus="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accountProfileLabel">Account Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="file" id="profilePictureInput" style="display: none;"
                    accept="image/jpeg,image/png,image/jpg">
                <div class="w-100 p-0 m-0 position-relative border-bottom"
                    style="height: 50px; margin-bottom: calc(50px + 25px) !important;">
                    <div class="position-absolute top-50 start-0 p-0 m-0 fw-semibold text-muted d-none d-md-flex align-items-center gap-2"
                        style="padding-left: calc(125px + 25px) !important; transform: translateY(-25%); max-width: calc(100% - 150px);">
                        <span class="accountName overflow-hidden w-100 fs-2"
                            style="text-overflow: ellipsis; white-space: nowrap;"></span>
                    </div>
                    {{-- Can Edit Profile Picture --}}
                    <div class="profile-item position-absolute top-100 start-0"
                        style="height: 100px; width: 100px; transform: translate(25%, -50%); z-index: 2;">
                        <img class="position-absolute p-0 m-0 object-fit-cover rounded-circle border-1 bg-body shadow-sm z-1"
                            style="height: 100%; width: 100%; cursor: pointer;" id="accountProfilePicture"
                            src="{{ asset('static/images/default_profile.svg') }}" alt="User profile image">
                        <div style="display: none; height: 100%; width: 100%; pointer-events: none; background-color: rgba(0,0,0,0.4); color: white;"
                            class="edit-profile-picture-button position-absolute top-0 start-0 rounded-circle z-2 align-items-center justify-content-center">
                            <i class="ti ti-edit"></i>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 border-md-end">
                        <div class="mb-2 d-block d-md-none" style="padding-inline: 25px;">
                            <label class="form-label fw-bold mb-0">Nama</label>
                            <p class="text-muted d-flex align-items-center gap-2">
                                <span class="accountName"></span>
                            </p>
                        </div>
                        {{-- Can Edit Email --}}
                        <div class="mb-2" style="padding-inline: 25px;">
                            <label class="form-label fw-bold mb-0">Email</label>
                            <p class="text-muted d-flex align-items-center profile-item gap-2">
                                <span id="accountEmail">email@example.com</span>
                                <i style="display: none; cursor: pointer;" class="ti ti-edit edit-email-button"></i>
                            </p>
                        </div>
                        <div class="mb-2" style="padding-inline: 25px;">
                            <label class="form-label fw-bold mb-0">NISN</label>
                            <p class="text-muted d-flex align-items-center gap-2">
                                <span id="accountNISN"></span>
                            </p>
                        </div>
                        <div class="mb-2" style="padding-inline: 25px;">
                            <label class="form-label fw-bold mb-0">Kelas</label>
                            <p class="text-muted d-flex align-items-center gap-2">
                                <span id="accountClass"></span>
                            </p>
                        </div>
                        <div class="mb-2" style="padding-inline: 25px;">
                            <label class="form-label fw-bold mb-0">Tanggal Lahir</label>
                            <p class="text-muted d-flex align-items-center gap-2">
                                <span id="accountDOB"></span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2" style="padding-inline: 25px;">
                            <label class="form-label fw-bold mb-0">Role</label>
                            <p class="text-muted d-flex align-items-center gap-2">
                                <span id="accountRole"></span>
                            </p>
                        </div>
                        <div class="mb-2" style="padding-inline: 25px;">
                            <label class="form-label fw-bold mb-0">Email Verified</label>
                            <p class="text-muted d-flex align-items-center gap-2">
                                <span id="accountEmailVerified"></span>
                            </p>
                        </div>
                        <div class="mb-2" style="padding-inline: 25px;">
                            <label class="form-label fw-bold mb-0">Dibuat Pada</label>
                            <p class="text-muted d-flex align-items-center gap-2">
                                <span id="accountCreatedAt"></span>
                            </p>
                        </div>
                        <div class="mb-2" style="padding-inline: 25px;">
                            <label class="form-label fw-bold mb-0">Terakhir Diperbarui</label>
                            <p class="text-muted d-flex align-items-center gap-2">
                                <span id="accountUpdatedAt"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .profile-item:hover .edit-profile-picture-button {
        display: flex !important;
    }
    .profile-item:hover .edit-email-button {
        display: inline-block !important;
    }
    .swal2-container {
        z-index: 3000 !important;
    }
</style>
<script>
    function getGreeting() {
        const hour = new Date().getHours();
        let greeting;
        if (hour >= 5 && hour < 12) {
            greeting = "Selamat Pagi";
        } else if (hour >= 12 && hour < 17) {
            greeting = "Selamat Siang";
        } else if (hour >= 17 && hour < 21) {
            greeting = "Selamat Sore";
        } else {
            greeting = "Selamat Malam";
        }
        return greeting;
    }
    document.getElementById('greeting').innerText = getGreeting();
    const userProfilePicture = document.getElementById('userProfilePicture');
    const userName = document.getElementById('userName');
    const userRole = document.getElementById('userRole');
    async function getUserTopbar() {
        try {
            const response = await axios.get('/api/me', {
                headers: {
                    'Authorization': `Bearer ${getAuthToken()}`
                }
            });
            if (response.status === 200 && response.data.status === true) {
                userProfilePicture.src = response.data.data.profile_picture_url;
                userName.innerText = response.data.data.fullname;
                userRole.innerText = response.data.data.role;
            } else {
                await Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.data.message ?? 'Gagal mengambil data profil pengguna!',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        } catch (error) {
            await Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.response?.data?.message ?? 'Gagal mengambil data profil pengguna!',
                showConfirmButton: false,
                timer: 1500
            });
        }
    }
    document.addEventListener('DOMContentLoaded', async () => {
        await getUserTopbar();
    });
    let accountProfileModal;
    document.addEventListener('DOMContentLoaded', () => {
        accountProfileModal = new bootstrap.Modal(document.getElementById('accountProfileModal'));
    });
    async function getUserProfileData() {
        try {
            const response = await axios.get('/api/profile', {
                headers: {
                    'Authorization': `Bearer ${getAuthToken()}`
                }
            });
            if (response.status === 200 && response.data.status === true) {
                const user = response.data.data;
                document.getElementById('accountProfilePicture').src = user.profile_picture_url ??
                    "{{ asset('static/images/default_profile.svg') }}";
                document.querySelectorAll('.accountName').forEach(el => {
                    el.textContent = user.student?.name ?? '-';
                });
                document.getElementById('accountEmail').textContent = user.email ?? '-';
                document.getElementById('accountNISN').textContent = user.student?.nisn ?? '-';
                document.getElementById('accountClass').textContent = user.student?.class?.class_name ?? '-';
                document.getElementById('accountDOB').textContent = user.student?.dob ? new Date(user.student.dob)
                    .toLocaleDateString() : '-';
                document.getElementById('accountRole').textContent = user.role ?? '-';
                document.getElementById('accountEmailVerified').textContent = user.email_verified_at ?
                    new Date(user.email_verified_at).toLocaleString() :
                    'Belum diverifikasi';
                document.getElementById('accountCreatedAt').textContent = user.created_at ?
                    new Date(user.created_at).toLocaleString() :
                    '-';
                document.getElementById('accountUpdatedAt').textContent = user.updated_at ?
                    new Date(user.updated_at).toLocaleString() :
                    '-';
            } else {
                await accountProfileModal.hide();
                await Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.data.message ?? 'Gagal mengambil data profil pengguna!',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        } catch (error) {
            await accountProfileModal.hide();
            await Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.response?.data?.message ?? 'Gagal mengambil data profil pengguna!',
                showConfirmButton: false,
                timer: 1500
            });
        }
    }
    document.getElementById('account-profile-button').addEventListener('click', async () => {
        await accountProfileModal.show();
        await getUserProfileData();
    });
    async function logout() {
        try {
            const logoutConfirm = await Swal.fire({
                icon: 'warning',
                title: 'Logout',
                text: 'Apakah Anda yakin ingin logout?',
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true
            });
            if (!logoutConfirm.isConfirmed) return;
            const response = await axios.post('/api/logout', {}, {
                headers: {
                    'Authorization': `Bearer ${getAuthToken()}`
                }
            });
            await Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.data.message ?? 'Berhasil logout!',
                showConfirmButton: false,
                timer: 1000
            }).then(async () => {
                window.location.href = "{{ route('login') }}";
            });
        } catch (e) {
            await Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: e.response?.data?.message ?? 'Terjadi kesalahan!',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then(async () => {
                window.location.href = "{{ route('login') }}";
            });
        }
    }
    document.getElementById('logout-button').addEventListener('click', async () => {
        await logout();
    });
    async function getNotifications() {
        try {
            const response = await axios.get('/api/notifications', {
                headers: {
                    'Authorization': `Bearer ${getAuthToken()}`
                }
            });
            if (response.status === 200 && response.data.status === true) {
                const notifications = response.data.data;
                const container = document.getElementById('notificationList');
                container.innerHTML = '';
                if (notifications.length === 0) {
                    container.innerHTML = `
                        <div class="list-group-item text-center text-muted">
                            Tidak ada notifikasi
                        </div>`;
                    return;
                }
                notifications.forEach(item => {
                    const isUnread = item.is_read === 0;
                    const iconColor = isUnread ? 'bg-light-warning' : 'bg-light-success';
                    const statusBadge = isUnread ?
                        '<div class="badge rounded-pill bg-light-danger">Unread</div>' :
                        '<div class="badge rounded-pill bg-light-secondary">Read</div>';
                    const notifElement = `
                        <div class="list-group-item list-group-item-action" data-id="${item.id}" style="cursor: ${isUnread ? 'pointer' : 'default'} !important;">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="user-avtar ${iconColor}">
                                        <i class="ti ti-bell"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-1">
                                    <span class="float-end text-muted">${item.created_at}</span>
                                    <h5 style="cursor: ${isUnread ? 'pointer' : 'default'} !important;">${item.title}</h5>
                                    <p class="text-body fs-6 mb-1">${item.body}</p>
                                    ${statusBadge}
                                </div>
                            </div>
                        </div>`;
                    container.insertAdjacentHTML('beforeend', notifElement);
                });
            } else {
                await Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.data.message ?? 'Gagal mengambil data notifikasi!',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        } catch (error) {
            await Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.response?.data?.message ?? 'Gagal mengambil data notifikasi!',
                showConfirmButton: false,
                timer: 1500
            });
        }
    }
    document.addEventListener('DOMContentLoaded', async () => {
        await getNotifications();
    });
    async function markNotificationAsRead(id, notifItem = null) {
        try {
            const response = await axios.patch(`/api/notifications/${id}/read`, {}, {
                headers: {
                    'Authorization': `Bearer ${getAuthToken()}`
                }
            });
            if (response.status === 200 && response.data.status === true) {
                if (notifItem) {
                    const badge = notifItem.querySelector('.badge');
                    if (badge) {
                        badge.classList.remove('bg-light-danger');
                        badge.classList.add('bg-light-secondary');
                        badge.textContent = 'Read';
                    }
                } else {
                    await getNotifications();
                }
            } else {
                await Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.data.message ?? 'Gagal menandai notifikasi sebagai telah dibaca!',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        } catch (error) {
            await Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.response?.data?.message ?? 'Gagal menandai notifikasi sebagai telah dibaca!',
                showConfirmButton: false,
                timer: 1500
            });
        }
    }
    document.getElementById('notificationList').addEventListener('click', async (e) => {
        const notifItem = e.target.closest('.list-group-item[data-id]');
        if (!notifItem) return;
        const badge = notifItem.querySelector('.badge');
        if (badge && badge.textContent === 'Read') {
            return;
        }
        const notificationId = notifItem.dataset.id;
        await markNotificationAsRead(notificationId, notifItem);
    });
    async function markAllNotificationsAsRead() {
        try {
            const readAllButton = document.getElementById('readAllNotifications');
            readAllButton.classList.add('disabled');
            const response = await axios.patch('/api/notifications/read-all', {}, {
                headers: {
                    'Authorization': `Bearer ${getAuthToken()}`
                }
            });
            if (response.status === 200 && response.data.status === true) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.data.message ??
                        'Berhasil menandai semua notifikasi sebagai telah dibaca!',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                await Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.data.message ?? 'Gagal menandai semua notifikasi sebagai telah dibaca!',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        } catch (error) {
            await Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.response?.data?.message ??
                    'Gagal menandai semua notifikasi sebagai telah dibaca!',
                showConfirmButton: false,
                timer: 1500
            });
        } finally {
            readAllButton.classList.remove('disabled');
        }
    }
    document.getElementById('readAllNotifications').addEventListener('click', async () => {
        await markAllNotificationsAsRead();
        await getNotifications();
    });
    async function sendPatchRequest(formData, loadingMessage) {
        Swal.fire({
            title: loadingMessage,
            text: 'Mohon tunggu...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        try {
            formData.append('_method', 'PATCH');
            const response = await axios.post(
                '/api/profile',
                formData, {
                    headers: {
                        'Authorization': `Bearer ${getAuthToken()}`,
                        'Content-Type': formData['type'] === 'email' ? 'application/json' :
                            'multipart/form-data'
                    }
                }
            );
            const data = response.data;
            if (data.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                });
                return true;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan saat memperbarui profil (Status 200).',
                });
                return false;
            }
        } catch (error) {
            const errorMessage = error.response ?
                error.response.data.message || 'Gagal terhubung ke API dengan status error.' :
                error.message || 'Terjadi error jaringan atau server.';
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: errorMessage,
            });
            return false;
        }
    }
    async function changeUserProfile(type) {
        const accountEmailSpan = document.getElementById('accountEmail');
        if (type === 'profile_picture') {
            const input = document.getElementById('profilePictureInput');
            if (!input) return;
            input.click();
            input.onchange = async (event) => {
                const file = event.target.files[0];
                if (!file) return;
                const previewURL = URL.createObjectURL(file);
                const result = await Swal.fire({
                    title: 'Pratinjau Gambar Profil',
                    html: `
                        <div class="d-flex flex-column align-items-center">
                            <img src="${previewURL}" alt="Preview" class="object-fit-cover" style="width:200px; height: 200px; object-position: center; border-radius:50%; box-shadow:0 0 10px rgba(0,0,0,0.3); margin-bottom:15px;">
                            <p class="text-muted">Apakah Anda ingin mengunggah gambar ini sebagai foto profil?</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Upload',
                    cancelButtonText: 'Batal',
                    focusConfirm: false,
                    allowOutsideClick: false,
                });
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('type', 'profile_picture');
                    formData.append('profile_picture', file);
                    const success = await sendPatchRequest(formData, 'Mengunggah Gambar Profil...');
                    if (success) {
                        await getUserTopbar();
                        await getUserProfileData();
                    }
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Dibatalkan',
                        text: 'Upload gambar profil dibatalkan.',
                        timer: 1200,
                        showConfirmButton: false
                    });
                }
                input.value = '';
                URL.revokeObjectURL(previewURL);
            };
        } else if (type === 'email') {
            const result = await Swal.fire({
                title: 'Perbarui Alamat Email',
                input: 'email',
                inputValue: accountEmailSpan ? accountEmailSpan.innerText : '',
                inputLabel: 'Alamat Email Baru',
                inputPlaceholder: 'Masukkan alamat email baru',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                allowOutsideClick: false,
                allowEscapeKey: true,
                allowEnterKey: true,
                focusConfirm: false,
                didOpen: (popup) => {
                    const input = popup.querySelector('input');
                    if (input) {
                        input.removeAttribute('readonly');
                        input.focus();
                    }
                }
            });
            if (result.isConfirmed) {
                const newEmail = result.value;
                const formData = new FormData();
                formData.append('type', 'email');
                formData.append('email', newEmail);
                const success = await sendPatchRequest(formData, 'Memperbarui Email...');
                if (success) {
                    await getUserProfileData();
                }
            }
        }
    }
    document.addEventListener('DOMContentLoaded', () => {
        const profileItem = document.querySelector('.profile-item');
        if (profileItem) {
            profileItem.addEventListener('click', () => changeUserProfile('profile_picture'));
        }
        const editEmailButton = document.querySelector('.edit-email-button');
        if (editEmailButton) {
            editEmailButton.addEventListener('click', (e) => {
                e.stopPropagation();
                changeUserProfile('email');
            });
        }
        document.querySelector('.accountName').innerText = 'Nama Pengguna Contoh';
        document.getElementById('accountEmail').innerText = 'email@contoh.com';
    });
</script>
