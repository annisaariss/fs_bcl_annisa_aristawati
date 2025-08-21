<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="">Logistik</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="">Log</a>
    </div>
    <ul class="sidebar-menu">
        @section('sidebar')
            <li class="{{ request()->routeIs('armada.*') ? 'active' : '' }}">
                <a href="{{ route('armada.index') }}" class="nav-link">
                    <i class="fas fa-truck"></i>
                    <span>Data Armada</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('shipments.*') ? 'active' : '' }}">
                <a href="{{ route('shipments.index') }}" class="nav-link">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Data Pengiriman</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                <a href="{{ route('bookings.index') }}" class="nav-link">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Data Pemesanan</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('checkins.*') ? 'active' : '' }}">
                <a href="{{ route('checkins.index') }}" class="nav-link">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Check-In Armada</span>
                </a>
            </li>

        @show
    </ul>
</aside>
