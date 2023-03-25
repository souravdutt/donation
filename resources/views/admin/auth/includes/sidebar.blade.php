    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand" href="{{ route('auth.dashboard') }}">
                <span class="align-middle">{{ env('APP_NAME', 'AdminPanel') }}</span>
            </a>

            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    Pages
                </li>

                <li class="sidebar-item @if(Route::is('auth.dashboard')) active @endif">
                    <a class="sidebar-link" href="{{ route('auth.dashboard') }}">
                        <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item @if(Route::is('auth.queries')) active @endif">
                    <a class="sidebar-link" href="{{ route('auth.queries') }}">
                        <i class="align-middle" data-feather="mail"></i> <span class="align-middle">Queries</span>
                    </a>
                </li>

                <li class="sidebar-item @if(Route::is('auth.donation')) active @endif">
                    <a class="sidebar-link" href="{{ route('auth.donation') }}">
                        <i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Donation</span>
                    </a>
                </li>

                <li class="sidebar-item @if(Route::is('auth.albums')) active @endif">
                    <a class="sidebar-link" href="{{ route('auth.albums') }}">
                        <i class="align-middle" data-feather="image"></i> <span class="align-middle">Albums</span>
                    </a>
                </li>

                <li class="sidebar-item @if(Route::is('auth.members')) active @endif">
                    <a class="sidebar-link" href="{{ route('auth.members') }}">
                        <i class="align-middle" data-feather="user"></i> <span class="align-middle">Members</span>
                    </a>
                </li>
            </ul>

        </div>
    </nav>
