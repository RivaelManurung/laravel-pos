<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        @php $role = Auth::check() ? Auth::user()->role : null; @endphp
        @if($role === 'cashier')
            <a href="{{ route('cashier.dashboard') }}" class="app-brand-link">
                <span class="app-brand-logo demo">
                    <i class='bx bx-store-alt text-primary'></i>
                </span>
                <span class="app-brand-text demo menu-text fw-bold ms-2">POS - CASHIER</span>
            </a>
        @elseif($role === 'manager')
            <a href="{{ route('manager.dashboard') }}" class="app-brand-link">
                <span class="app-brand-logo demo">
                    <i class='bx bx-store-alt text-primary'></i>
                </span>
                <span class="app-brand-text demo menu-text fw-bold ms-2">POS - MANAGER</span>
            </a>
        @else
            <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
                <span class="app-brand-logo demo">
                    <i class='bx bx-store-alt text-primary'></i>
                </span>
                <span class="app-brand-text demo menu-text fw-bold ms-2">POS SYSTEM</span>
            </a>
        @endif
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
        </a>
    </div>
    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        {{-- Dashboard --}}
        @if($role === 'cashier')
            <li class="menu-item {{ Request::is('cashier/dashboard*') ? 'active' : '' }}">
                <a href="{{ route('cashier.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-smile"></i>
                    <div>Dashboard</div>
                </a>
            </li>

            {{-- POS & Orders for cashier --}}
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Transaksi</span>
            </li>
            <li class="menu-item {{ Request::is('cashier/pos*') ? 'active' : '' }}">
                <a href="{{ route('cashier.pos') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-basket"></i>
                    <div>POS</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('cashier/orders*') ? 'active' : '' }}">
                <a href="{{ route('cashier.orders.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-receipt"></i>
                    <div>Order Saya</div>
                </a>
            </li>

        @elseif($role === 'manager')
            <li class="menu-item {{ Request::is('manager/dashboard*') ? 'active' : '' }}">
                <a href="{{ route('manager.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-smile"></i>
                    <div>Dashboard</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Laporan</span>
            </li>
            <li class="menu-item {{ Request::is('manager/reports*') ? 'active' : '' }}">
                <a href="{{ route('manager.reports.sales') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-stats"></i>
                    <div>Laporan Penjualan</div>
                </a>
            </li>

        @else
            <li class="menu-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-smile"></i>
                    <div>Dashboard</div>
                </a>
            </li>

            {{-- Transaksi --}}
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Transaksi</span>
            </li>
            <li class="menu-item {{ Request::is('admin/orders*') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-history"></i>
                    <div>Riwayat Transaksi</div>
                </a>
            </li>

            {{-- Data Master --}}
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Data Master</span>
            </li>
            <li class="menu-item {{ Request::is('admin/products*') ? 'active' : '' }}">
                <a href="{{ route('admin.products.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-package"></i>
                    <div>Manajemen Produk</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('admin/categories*') ? 'active' : '' }}">
                <a href="{{ route('admin.categories.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-category"></i>
                    <div>Kategori Produk</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('admin/customers*') ? 'active' : '' }}">
                <a href="{{ route('admin.customers.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-circle"></i>
                    <div>Manajemen Pelanggan</div>
                </a>
            </li>

            {{-- Pengguna --}}
            @if (Auth::check() && Auth::user()->role === 'admin')
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Pengguna</span>
            </li>
            <li class="menu-item {{ Request::is('admin/users*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>Manajemen Pengguna</div>
                </a>
            </li>
            @endif

            {{-- Laporan --}}
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Laporan</span>
            </li>
            <li class="menu-item {{ Request::is('admin/reports*') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.sales') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-stats"></i>
                    <div>Laporan Penjualan</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('admin/reports/inventory*') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.inventory') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-box"></i>
                    <div>Laporan Stok</div>
                </a>
            </li>
        @endif
    </ul>
</aside>