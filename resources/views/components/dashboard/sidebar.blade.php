<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ url('https://reftech.id/wp-content/uploads/2021/10/Reftech-Logo-Hitam.png') }}"
                    alt="logo-reftech" width="35%">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.4854 4.88844C11.0081 4.41121 10.2344 4.41121 9.75715 4.88844L4.51028 10.1353C4.03297 10.6126 4.03297 11.3865 4.51028 11.8638L9.75715 17.1107C10.2344 17.5879 11.0081 17.5879 11.4854 17.1107C11.9626 16.6334 11.9626 15.8597 11.4854 15.3824L7.96672 11.8638C7.48942 11.3865 7.48942 10.6126 7.96672 10.1353L11.4854 6.61667C11.9626 6.13943 11.9626 5.36568 11.4854 4.88844Z"
                    fill="currentColor" fill-opacity="0.6" />
                <path
                    d="M15.8683 4.88844L10.6214 10.1353C10.1441 10.6126 10.1441 11.3865 10.6214 11.8638L15.8683 17.1107C16.3455 17.5879 17.1192 17.5879 17.5965 17.1107C18.0737 16.6334 18.0737 15.8597 17.5965 15.3824L14.0778 11.8638C13.6005 11.3865 13.6005 10.6126 14.0778 10.1353L17.5965 6.61667C18.0737 6.13943 18.0737 5.36568 17.5965 4.88844C17.1192 4.41121 16.3455 4.41121 15.8683 4.88844Z"
                    fill="currentColor" fill-opacity="0.38" />
            </svg>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @if (auth::user()->role == 'Admin')
            <!-- Dashboards -->
            <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                    <div data-i18n="Dashboards">Dashboards</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-phone-incoming-outgoing-outline"></i>
                    <div data-i18n="Activities">Activities</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('reports') ? 'active' : '' }}">
                <a href="{{ url('/reports') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-finance"></i>
                    <div data-i18n="Reports">Reports</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('overview') ? 'active' : '' }}">
                <a href="{{ url('/overview') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-eye-outline"></i>
                    <div data-i18n="Overview">Overview</div>
                </a>
            </li>
            <!-- Layouts -->
            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Client</span>
            </li>
            <li
                class="menu-item {{ request()->is('leads') || request()->is('leads/detail/*') || request()->is('existing') || request()->is('existing/*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-account-group-outline"></i>
                    <div data-i18n="Client">Client</div>
                </a>

                <ul class="menu-sub">
                    <li
                        class="menu-item {{ request()->is('leads') || request()->is('leads/detail/*') ? 'active' : '' }}">
                        <a href="{{ url('leads') }}" class="menu-link">
                            <div data-i18n="Leads">Leads</div>
                        </a>
                    </li>

                    <li
                        class="menu-item {{ request()->is('existing') || request()->is('existing/*') ? 'active' : '' }}">
                        <a href="{{ route('existing.index') }}" class="menu-link">
                            <div data-i18n="CRM Existing">CRM Existing</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li
                class="menu-item {{ request()->is('quotation') || request()->is('quotation/*') || request()->is('po') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-email-outline"></i>
                    <div data-i18n="Quotation">Quotation</div>
                </a>
                <ul class="menu-sub">
                    <li
                        class="menu-item {{ request()->is('quotation') || request()->is('quotation/*') ? 'active' : '' }}">
                        <a href="{{ route('quotation.index') }}" class="menu-link">
                            <div data-i18n="Quotation Leads">Leads</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div data-i18n="Quotation Unit">Customer Unit</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('po') ? 'active' : '' }}">
                        <a href="{{ route('quotation.po') }}" class="menu-link">
                            <div data-i18n="Done PO">Done PO</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item {{ request()->is('visits/*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-office-building-marker-outline"></i>
                    <div data-i18n="Visit">Visit</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('visits/leads') ? 'active' : '' }}">
                        <a href="{{ url('visits/leads') }}" class="menu-link">
                            <div data-i18n="Leads">Leads</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div data-i18n="Customer">Customer</div>
                        </a>
                    </li>
                </ul>
            </li>


            <li
                class="menu-item {{ request()->is('service-reports') || request()->is('service-reports/*') ? 'active' : '' }}">
                <a href="{{ route('service-reports.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-file-chart-outline"></i>
                    <div data-i18n="Service Report">Service Report</div>
                </a>
            </li>

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">E-Stock</span>
            </li>

            <li class="menu-item {{ request()->is('product') || request()->is('product/*') ? 'active' : '' }}">
                <a href="{{ route('product.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-package-variant"></i>
                    <div data-i18n="Product">Product</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('product-in') || request()->is('product-in/*') ? 'active' : '' }}">
                <a href="{{ route('product-in.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-package-variant-plus"></i>
                    <div data-i18n="Product-In">Product-In</div>
                </a>
            </li>
            <li
                class="menu-item {{ request()->is('product-out') || request()->is('product-out/*') ? 'active' : '' }}">
                <a href="{{ route('product-out.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-package-variant-minus"></i>
                    <div data-i18n="Product-Out">Product-Out</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('stock') || request()->is('stock/*') ? 'active' : '' }}">
                <a href="{{ route('stock.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-package-variant-closed-check"></i>
                    <div data-i18n="Stock">Stock</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('sale-report') || request()->is('sale-report/*') ? 'active' : '' }}">
                <a href="{{ route('sale-report.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-chart-box-outline"></i>
                    <div data-i18n="Reports">Reports</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ url('pending-po') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-cart-arrow-down"></i>
                    <div data-i18n="Pending PO">Pending PO</div>
                </a>
            </li>
            
            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Employee</span>
            </li>
        @elseif (auth::user()->role == 'Sales')
            <!-- Dashboards -->
            <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                    <div data-i18n="Dashboards">Dashboards</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-phone-incoming-outgoing-outline"></i>
                    <div data-i18n="Activities">Activities</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('reports') ? 'active' : '' }}">
                <a href="{{ url('/reports') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-finance"></i>
                    <div data-i18n="Reports">Reports</div>
                </a>
            </li><!-- Layouts -->
            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Client</span>
            </li>
            <li
                class="menu-item {{ request()->is('leads') || request()->is('leads/detail/*') || request()->is('existing') || request()->is('existing/*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-account-group-outline"></i>
                    <div data-i18n="Client">Client</div>
                </a>

                <ul class="menu-sub">
                    <li
                        class="menu-item {{ request()->is('leads') || request()->is('leads/detail/*') ? 'active' : '' }}">
                        <a href="{{ url('leads') }}" class="menu-link">
                            <div data-i18n="Leads">Leads</div>
                        </a>
                    </li>

                    <li
                        class="menu-item {{ request()->is('existing') || request()->is('existing/*') ? 'active' : '' }}">
                        <a href="{{ route('existing.index') }}" class="menu-link">
                            <div data-i18n="CRM Existing">CRM Existing</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li
                class="menu-item {{ request()->is('quotation') || request()->is('quotation/*') || request()->is('po') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-email-outline"></i>
                    <div data-i18n="Quotation">Quotation</div>
                </a>
                <ul class="menu-sub">
                    <li
                        class="menu-item {{ request()->is('quotation') || request()->is('quotation/*') || request()->is('quotation/*') ? 'active' : '' }}">
                        <a href="{{ route('quotation.index') }}" class="menu-link">
                            <div data-i18n="Quotation Leads">Quotation Leads</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div data-i18n="Quotation Unit">Quotation Unit</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('po') ? 'active' : '' }}">
                        <a href="{{ route('quotation.po') }}" class="menu-link">
                            <div data-i18n="Done PO">Done PO</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item {{ request()->is('visits/*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-office-building-marker-outline"></i>
                    <div data-i18n="Visit">Visit</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('visits/leads') ? 'active' : '' }}">
                        <a href="{{ url('visits/leads') }}" class="menu-link">
                            <div data-i18n="Leads">Leads</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div data-i18n="Customer">Customer</div>
                        </a>
                    </li>
                </ul>
            </li>


            <li
                class="menu-item {{ request()->is('service-reports') || request()->is('service-reports/*') ? 'active' : '' }}">
                <a href="{{ route('service-reports.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-file-chart-outline"></i>
                    <div data-i18n="Service Report">Service Report</div>
                </a>
            </li>
            
            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">E-Stock</span>
            </li>

            <li class="menu-item {{ request()->is('product') || request()->is('product/*') ? 'active' : '' }}">
                <a href="{{ route('product.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-package-variant"></i>
                    <div data-i18n="Product">Product</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('product-in') || request()->is('product-in/*') ? 'active' : '' }}">
                <a href="{{ route('product-in.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-package-variant-plus"></i>
                    <div data-i18n="Product-In">Product-In</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ url('pending-po') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-cart-arrow-down"></i>
                    <div data-i18n="Pending PO">Pending PO</div>
                </a>
            </li>
        @else
            <!-- Dashboards -->
            <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                    <div data-i18n="Dashboards">Dashboards</div>
                </a>
            </li>
            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Technician</span>
            </li>
            <li
                class="menu-item {{ request()->is('service-reports') || request()->is('service-reports/*') ? 'active' : '' }}">
                <a href="{{ route('service-reports.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-file-chart-outline"></i>
                    <div data-i18n="Service Report">Service Report</div>
                </a>
            </li>
        @endif

    </ul>
</aside>
