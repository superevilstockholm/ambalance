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
                            <h5>
                                All Notification
                                <span class="badge bg-warning rounded-pill ms-1">01</span>
                            </h5>
                        </div>
                        <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative"
                            style="max-height: calc(100vh - 215px)">
                            <div class="list-group list-group-flush w-100">
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="user-avtar bg-light-success"><i
                                                    class="ti ti-building-store"></i></div>
                                        </div>
                                        <div class="flex-grow-1 ms-1">
                                            <span class="float-end text-muted">3 min ago</span>
                                            <h5>Store Verification Done</h5>
                                            <p class="text-body fs-6">We have successfully received your request.
                                            </p>
                                            <div class="badge rounded-pill bg-light-danger">Unread</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('static/images/default_profile.svg') }}" alt="user-image"
                                                class="user-avtar" />
                                        </div>
                                        <div class="flex-grow-1 ms-1">
                                            <span class="float-end text-muted">10 min ago</span>
                                            <h5>Joseph William</h5>
                                            <p class="text-body fs-6">It is a long established fact that a reader
                                                will be distracted</p>
                                            <div class="badge rounded-pill bg-light-success">Confirmation of
                                                Account</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="text-center py-2">
                            <a href="#!" class="link-primary">Mark as all read</a>
                        </div>
                    </div>
                </li>
                {{-- User Profile --}}
                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link head-link-primary dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img id="userProfilePicture" src="{{ asset('static/images/default_profile.svg') }}" alt="user-image"
                            class="user-avtar" />
                        <span>
                            <i class="ti ti-settings"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header">
                            <h4 class="d-flex align-items-center gap-1">
                                <span><span id="greeting"></span>,</span>
                                <span class="small text-muted text-truncate d-inline-block" style="max-width: 120px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;" id="userName">User</span>
                            </h4>
                            <p class="text-muted" id="userRole">Unknown</p>
                            <hr />
                            <div class="profile-notification-scroll position-relative"
                                style="max-height: calc(100vh - 280px)">
                                <a href="../application/account-profile-v1.html" class="dropdown-item">
                                    <i class="ti ti-settings"></i>
                                    <span>Account Settings</span>
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
    async function getUserProfileData() {
        try {
            const response = await axios.get('/api/me', { headers: {'Authorization': `Bearer ${getAuthToken()}`} });
            if (response.status === 200 && response.data.status === true) {
                userProfilePicture.src = response.data.data.profile_picture_url;
                userName.innerText = response.data.data.fullname;
                userRole.innerText = response.data.data.role;
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.response?.data?.message ?? 'Gagal mengambil data profil pengguna!',
                showConfirmButton: false,
                timer: 1500
            });
        }
    }
    document.addEventListener('DOMContentLoaded', async () => {
        await getUserProfileData();
    });

    async function logout() {
        try {
            const response = await axios.post('/api/logout', {}, { headers: {'Authorization': `Bearer ${getAuthToken()}`} });
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.data.message ?? 'Berhasil logout!',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            })
        } catch (e) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: e.response?.data?.message ?? 'Terjadi kesalahan!',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            })
        } finally {
            window.location.href = "{{ route('login') }}";
        }
    }

    document.getElementById('logout-button').addEventListener('click', async () => {
        await logout();
    });
</script>
