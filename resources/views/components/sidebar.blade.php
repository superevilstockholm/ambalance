<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('student.dashboard') }}" class="b-brand text-primary fs-2 fw-semibold d-flex align-items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-pig-money"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 11v.01" /><path d="M5.173 8.378a3 3 0 1 1 4.656 -1.377" /><path d="M16 4v3.803a6.019 6.019 0 0 1 2.658 3.197h1.341a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-1.342c-.336 .95 -.907 1.8 -1.658 2.473v2.027a1.5 1.5 0 0 1 -3 0v-.583a6.04 6.04 0 0 1 -1 .083h-4a6.04 6.04 0 0 1 -1 -.083v.583a1.5 1.5 0 0 1 -3 0v-2l0 -.027a6 6 0 0 1 4 -10.473h2.5l4.5 -3h0z" /></svg>
                Ambalance
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                @foreach ($meta['sidebarItems'] as $key => $item)
                    <li class="pc-item pc-caption">
                        <label>{{ $key }}</label>
                    </li>
                    @foreach ($item as $sub_item)
                        <li class="pc-item{{ Route::currentRouteName() === $sub_item['route'] ? ' active' : '' }}">
                            <a href="{{ route($sub_item['route']) }}" class="pc-link">
                                <span class="pc-micon">
                                    <i class="{{ $sub_item['icon'] }}"></i>
                                </span>
                                <span class="pc-mtext">
                                    {{  $sub_item['label'] }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                @endforeach
            </ul>
            <div class="w-100 text-center">
                <div class="badge theme-version badge rounded-pill bg-light text-dark f-12"></div>
            </div>
        </div>
    </div>
</nav>
