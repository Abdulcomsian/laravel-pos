<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <div class="side__bar__top">
    <a href="{{route('home')}}" class="brand-link">
        <img src="{{ asset('images/logo.png') }}" alt="AdminLTE Logo" class="brand-image"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->getAvatar() }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->getFullname() }}</a>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview">
                    <a href="{{route('home')}}" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('products.index') }}" class="nav-link {{ activeSegment('products') }}">
                        <!-- <i class="nav-icon fas fa-th-large"></i> -->
                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/product.svg" alt="">
                        <p>Products</p>
                        <span class="menu-arrow"></span>
                    </a>
                </li>
                <!-- <li class="nav-item has-treeview">
                    <a href="{{ route('cart.index') }}" class="nav-link {{ activeSegment('cart') }}">
                        <i class="nav-icon fas fa-cart-plus"></i>
                        <p>Open POS</p>
                        <span class="menu-arrow"></span>
                    </a>
                </li> -->
                <li class="nav-item has-treeview">
                    <a href="{{ route('orders.index') }}" class="nav-link {{ activeSegment('orders') }}">
                        <!-- <i class="nav-icon fas fa-cart-plus"></i> -->
                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/purchase1.svg" alt="">
                        <p>Orders</p>
                        <span class="menu-arrow"></span>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('customers.index') }}" class="nav-link {{ activeSegment('customers') }}">
                        <!-- <i class="nav-icon fas fa-users"></i> -->
                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/users1.svg" alt="">
                        <p>Customers</p>
                        <span class="menu-arrow"></span>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('settings.index') }}" class="nav-link {{ activeSegment('settings') }}">
                        <!-- <i class="nav-icon fas fa-cogs"></i> -->
                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/settings.svg" alt="">
                        <p>Settings</p>
                        <span class="menu-arrow"></span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link" onclick="document.getElementById('logout-form').submit()">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                        <form action="{{route('logout')}}" method="POST" id="logout-form">
                            @csrf
                        </form>
                    </a>
                </li> -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
