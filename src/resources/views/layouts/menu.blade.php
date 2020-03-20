        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{ url('/') }}"
                                aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span
                                    class="hide-menu">Dashboard</span></a></li>
                        <li class="list-divider"></li>
                        <li class="nav-small-cap"><span class="hide-menu">Applications</span></li>
                        <li class="sidebar-item">
                            <a class="sidebar-link sidebar-link" href="#" aria-expanded="false">
                                <i data-feather="users" class="feather-icon"></i>
                                <span class="hide-menu">Customers</span></a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link sidebar-link" href="#" aria-expanded="false">
                                <i data-feather="globe" class="feather-icon"></i>
                                <span class="hide-menu">Suppliers</span></a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <i data-feather="tag" class="feather-icon"></i>
                                <span class="hide-menu">Products</span></a>
                            <!-- SubMenu -->
                            <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                <li class="sidebar-item"><a href="{{ route('product_dimensions.index') }}" class="sidebar-link">
                                        <span class="hide-menu">Product Dimensions</span></a>
                                </li>
                                <li class="sidebar-item"><a href="{{ route('category.index') }}" class="sidebar-link">
                                        <span class="hide-menu">Categories</span></a>
                                </li>
                                <li class="sidebar-item"><a href="{{ route('product.index') }}" class="sidebar-link">
                                        <span class="hide-menu">Products</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link sidebar-link" href="{{ route('stock.index') }}" aria-expanded="false">
                                <i data-feather="clipboard" class="feather-icon"></i>
                                <span class="hide-menu">Inventory Stock</span></a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <i data-feather="download" class="feather-icon"></i>
                                <span class="hide-menu">Check IN </span></a>
                                    <!-- SubMenu -->
                                    <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                        <li class="sidebar-item"><a href="{{ route('purchases.index') }}" class="sidebar-link">
                                            <span class="hide-menu">Purchases - PO</span></a>
                                        </li>
                                        <li class="sidebar-item"><a href="/rma" class="sidebar-link">
                                            <span class="hide-menu">RMA</span></a>
                                        </li>
                                        <li class="sidebar-item"><a href="/borrow" class="sidebar-link">
                                                <span class="hide-menu">Return Borrowed</span></a>
                                        </li>
                                    </ul>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <i data-feather="upload" class="feather-icon"></i>
                                <span class="hide-menu">Check OUT </span></a>
                            <!-- SubMenu -->
                            <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                <li class="sidebar-item"><a href="{{ route('order.index') }}" class="sidebar-link">
                                        <span class="hide-menu">Sales</span></a>
                                </li>
                                <li class="sidebar-item"><a href="/lend" class="sidebar-link">
                                        <span class="hide-menu">Lend</span></a>
                                </li>
                                <li class="sidebar-item"><a href="/refurbishment" class="sidebar-link">
                                        <span class="hide-menu">Refurbishment</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-small-cap"><span class="hide-menu">Extra</span></li>
                        <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="#" aria-expanded="false">
                            <i data-feather="user-minus" class="feather-icon"></i>
                            <span class="hide-menu">Logout</span></a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
