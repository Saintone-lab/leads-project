<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                {{-- reftech --}}
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
        @if (auth::user()?->role == 'Admin')
            <!-- Dashboards -->
            <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                    <div data-i18n="Dashboards">Dashboards</div>
                </a>
            </li>
            {{-- <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-phone-incoming-outgoing-outline"></i>
                    <div data-i18n="Activities">Activities</div>
                </a>
            </li> --}}
            {{-- <li class="menu-item {{ request()->is('reports') ? 'active' : '' }}">
                <a href="{{ url('/reports') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-finance"></i>
                    <div data-i18n="Reports">Reports</div>
                </a>
            </li> --}}
            <li
                class="menu-item {{ request()->is('overview') || request()->is('overview/*') || request()->is('overview/*/*') ? 'active' : '' }}">
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
                            <div data-i18n="Customers">Customers</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li
                class="menu-item {{ request()->is('quotation') || request()->is('quotation/*') || request()->is('po') || request()->is('loss') || request()->is('po/sales/*') || request()->is('quote/*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-email-outline"></i>
                    <div data-i18n="Quotation">Quotation</div>
                </a>
                <ul class="menu-sub">
                    <li
                        class="menu-item {{ request()->is('quotation') || request()->is('quotation/*') || request()->is('quotation/*') ? 'active' : '' }}">
                        <a href="{{ route('quotation.index') }}" class="menu-link">
                            <div data-i18n="Quotation">Quotation</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('quote/*') || request()->is('quote/*/*') ? 'active' : '' }}">
                        <a href="{{ route('index-unit.quotation') }}" class="menu-link">
                            <div data-i18n="Quotation Unit">Quotation Unit</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('po') || request()->is('po/sales/*') ? 'active' : '' }}">
                        <a href="{{ route('quotation.po') }}" class="menu-link">
                            <div data-i18n="Done PO">Done PO</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('loss') ? 'active' : '' }}">
                        <a href="{{ route('quotation.loss') }}" class="menu-link">
                            <div data-i18n="Loss">Loss</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- <li class="menu-item {{ request()->is('visits/*') ? 'open' : '' }}">
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
            </li> --}}
            <li
                class="menu-item {{ request()->is('service-reports') || request()->is('service-reports/*') ? 'active' : '' }}">
                <a href="{{ route('service-reports.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-file-chart-outline"></i>
                    <div data-i18n="Service Report">Service Report</div>
                </a>
            </li>
            <li
                class="menu-item {{ request()->is('service-manager') || request()->is('service-manager/*') ? 'active' : '' }}">
                <a href="{{ route('service-manager.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-file-chart-outline"></i>
                    <div data-i18n="Monitoring Fajar Paper">Monitoring Fajar Paper</div>
                </a>
            </li>

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Accounting</span>
            </li>
            <li
                class="menu-item {{ request()->is('contract') || request()->is('selling/contract') || request()->is('order/contract') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-book-check-outline"></i>
                    <div data-i18n="SC/CO">SC/CO</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('contract') ? 'active' : '' }}">
                        <a href="{{ route('contract.index') }}" class="menu-link">
                            <div data-i18n="Request">Request</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('selling/contract') ? 'active' : '' }}">
                        <a href="{{ route('index.selling') }}" class="menu-link">
                            <div data-i18n="Selling Contract">Selling Contract</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('order/contract') ? 'active' : '' }}">
                        <a href="{{ route('index.order') }}" class="menu-link">
                            <div data-i18n="Confirm Order">Confirm Order</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li
                class="menu-item {{ request()->is('invoice') || request()->is('invoice/*') || request()->is('request/invoice') || request()->is('request/invoice/*') || request()->is('index/invoice/kojisha') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-file-document-check-outline"></i>
                    <div data-i18n="Invoice">Invoice</div>
                </a>
                <ul class="menu-sub">
                    <li
                        class="menu-item {{ request()->is('request/invoice') || request()->is('request/invoice/*') ? 'active' : '' }}">
                        <a href="{{ route('invoice.request') }}" class="menu-link">
                            <div data-i18n="Request">Request</div>
                        </a>
                    </li>
                    <li
                        class="menu-item {{ request()->is('invoice') || request()->is('invoice/*') ? 'active' : '' }}">
                        <a href="{{ route('invoice.index') }}" class="menu-link">
                            <div data-i18n="Invoice Reftech">Invoice Reftech</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('index/invoice/kojisha') ? 'active' : '' }}">
                        <a href="{{ route('invoice.index_kojisha') }}" class="menu-link">
                            <div data-i18n="Invoice Kojisha">Invoice Kojisha</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Prospect</span>
            </li>

            <li class="menu-item {{ request()->is('prospect') || request()->is('prospect/*') ? 'active' : '' }}">
                <a href="{{ route('prospect.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-details-outline"></i>
                    <div data-i18n="Prospect">Prospect</div>
                    @if (@$noSaleProspect >= 1)
                        <div class="badge bg-danger rounded-pill ms-auto">{{ $noSaleProspect }}</div>
                    @endif
                </a>
            </li>

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Notulen</span>
            </li>

            <li class="menu-item {{ request()->is('notulen') || request()->is('notulen/*') ? 'active' : '' }}">
                <a href="{{ route('notulen.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-box-outline"></i>
                    <div data-i18n="notulen">Notulen</div>
                    {{-- @if (@$leveledProspect >= 1)
                        <div class="badge bg-danger rounded-pill ms-auto">{{ $leveledProspect }}</div>
                    @endif --}}
                </a>
            </li>

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">E-Stock</span>
            </li>

            <li class="menu-item {{ request()->is('master/product') ? 'active' : '' }}">
                <a href="{{ route('master.product') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-package"></i>
                    <div data-i18n="Master">Master</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('product') || request()->is('product/*') ? 'active' : '' }}">
                <a href="{{ route('product.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-package-variant"></i>
                    <div data-i18n="Product">Product</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('unit') || request()->is('unit/*') ? 'active' : '' }}">
                <a href="{{ route('unit.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-desktop-tower"></i>
                    <div data-i18n="Unit">Unit</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('cor-factor/calculator') ? 'active' : '' }}">
                <a href="{{ route('calculator.correction') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-desktop-tower"></i>
                    <div data-i18n="Correction Factor Calc">Correction Factor Calc</div>
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
            <li
                class="menu-item {{ request()->is('sale-report') || request()->is('sale-report/*') ? 'active' : '' }}">
                <a href="{{ route('sale-report.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-chart-box-outline"></i>
                    <div data-i18n="Reports">Reports</div>
                </a>
            </li>


            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Library</span>
            </li>
            <li class="menu-item {{ request()->is('library/index/*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-library-shelves"></i>
                    <div data-i18n="Library">Library</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('library/index/marktool') ? 'active' : '' }}">
                        <a href="{{ route('marktool.index') }}" class="menu-link">
                            <div data-i18n="Marketing Tools">Marketing Tools</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/brosur') ? 'active' : '' }}">
                        <a href="{{ route('brosur.index') }}" class="menu-link">
                            <div data-i18n="Brosur">Brosur</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/partlist') ? 'active' : '' }}">
                        <a href="{{ route('partlist.index') }}" class="menu-link">
                            <div data-i18n="Partlist">Partlist</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/manbook') ? 'active' : '' }}">
                        <a href="{{ route('manbook.index') }}" class="menu-link">
                            <div data-i18n="Manual Book">Manual Book</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Employee</span>
            </li>
            <li class="menu-item {{ request()->is('employee') || request()->is('employee/*') ? 'active' : '' }}">
                <a href="{{ route('employee.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-circle-outline"></i>
                    <div data-i18n="User">User</div>
                </a>
            </li>
            <li
                class="menu-item {{ request()->is('unit-global') || request()->is('unit-global/*') ? 'active' : '' }}">
                <a href="{{ route('unit-global.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-desktop-tower"></i>
                    <div data-i18n="Unit Global">Unit Global</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ url('pending-po') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-cart-arrow-down"></i>
                    <div data-i18n="Pending PO">Pending PO</div>
                </a>
            </li>
        @elseif (auth::user()?->role == 'Sales')
            <!-- Dashboards -->
            <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                    <div data-i18n="Dashboards">Dashboards</div>
                </a>
            </li>
            {{-- <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-phone-incoming-outgoing-outline"></i>
                    <div data-i18n="Activities">Activities</div>
                </a>
            </li> --}}
            <li class="menu-item {{ request()->is('reports') ? 'active' : '' }}">
                <a href="{{ url('/reports') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-finance"></i>
                    <div data-i18n="Reports">Reports</div>
                </a>
            </li>
            <li
                class="menu-item {{ request()->is('overview') || request()->is('overview/*') || request()->is('overview/*/*') ? 'active' : '' }}">
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
                            <div data-i18n="Customers">Customers</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li
                class="menu-item {{ request()->is('quotation') || request()->is('quotation/*') || request()->is('po') || request()->is('loss') || request()->is('po/sales/*') || request()->is('quote/*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-email-outline"></i>
                    <div data-i18n="Quotation">Quotation</div>
                </a>
                <ul class="menu-sub">
                    <li
                        class="menu-item {{ request()->is('quotation') || request()->is('quotation/*') || request()->is('quotation/*') ? 'active' : '' }}">
                        <a href="{{ route('quotation.index') }}" class="menu-link">
                            <div data-i18n="Quotation">Quotation</div>
                        </a>
                    </li>
                    <li
                        class="menu-item {{ request()->is('quote/*') || request()->is('quote/*/*') ? 'active' : '' }}">
                        <a href="{{ route('index-unit.quotation') }}" class="menu-link">
                            <div data-i18n="Quotation Unit">Quotation Unit</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('po') ? 'active' : '' }}">
                        <a href="{{ route('quotation.po') }}" class="menu-link">
                            <div data-i18n="Done PO">Done PO</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('loss') ? 'active' : '' }}">
                        <a href="{{ route('quotation.loss') }}" class="menu-link">
                            <div data-i18n="Loss">Loss</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- <li class="menu-item {{ request()->is('visits/*') ? 'open' : '' }}">
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
            </li> --}}


            <li
                class="menu-item {{ request()->is('service-reports') || request()->is('service-reports/*') ? 'active' : '' }}">
                <a href="{{ route('service-reports.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-file-chart-outline"></i>
                    <div data-i18n="Service Report">Service Report</div>
                </a>
            </li>
            @if (auth::user()->id == 4)
                <li
                    class="menu-item {{ request()->is('service-manager') || request()->is('service-manager/*') ? 'active' : '' }}">
                    <a href="{{ route('service-manager.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-file-chart-outline"></i>
                        <div data-i18n="Monitoring Fajar Paper">Monitoring Fajar Paper</div>
                    </a>
                </li>
            @endif

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">E-Stock</span>
            </li>

            <li class="menu-item {{ request()->is('product') || request()->is('product/*') ? 'active' : '' }}">
                <a href="{{ route('product.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-package-variant"></i>
                    <div data-i18n="Spare Part">Spare Part</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('unit') || request()->is('unit/*') ? 'active' : '' }}">
                <a href="{{ route('unit.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-desktop-tower"></i>
                    <div data-i18n="Unit">Unit</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('cor-factor/calculator') ? 'active' : '' }}">
                <a href="{{ route('calculator.correction') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-desktop-tower"></i>
                    <div data-i18n="Correction Factor Calc">Correction Factor Calc</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ url('pending-po') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-cart-arrow-down"></i>
                    <div data-i18n="Pending PO">Pending PO</div>
                </a>
            </li>

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Prospect</span>
            </li>

            <li class="menu-item {{ request()->is('prospect') || request()->is('prospect/*') ? 'active' : '' }}">
                <a href="{{ route('prospect.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-details-outline"></i>
                    <div data-i18n="Prospect">Prospect</div>
                    @if (@$leveledProspect >= 1)
                        <div class="badge bg-danger rounded-pill ms-auto">{{ $leveledProspect }}</div>
                    @endif
                </a>
            </li>

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Notulen</span>
            </li>

            <li class="menu-item {{ request()->is('notulen') || request()->is('notulen/*') ? 'active' : '' }}">
                <a href="{{ route('notulen.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-box-outline"></i>
                    <div data-i18n="notulen">Notulen</div>
                    {{-- @if (@$leveledProspect >= 1)
                        <div class="badge bg-danger rounded-pill ms-auto">{{ $leveledProspect }}</div>
                    @endif --}}
                </a>
            </li>

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Library</span>
            </li>
            <li class="menu-item {{ request()->is('library/index/*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-library-shelves"></i>
                    <div data-i18n="Library">Library</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('library/index/marktool') ? 'active' : '' }}">
                        <a href="{{ route('marktool.index') }}" class="menu-link">
                            <div data-i18n="Marketing Tools">Marketing Tools</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/brosur') ? 'active' : '' }}">
                        <a href="{{ route('brosur.index') }}" class="menu-link">
                            <div data-i18n="Brosur">Brosur</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/partlist') ? 'active' : '' }}">
                        <a href="{{ route('partlist.index') }}" class="menu-link">
                            <div data-i18n="Partlist">Partlist</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/manbook') ? 'active' : '' }}">
                        <a href="{{ route('manbook.index') }}" class="menu-link">
                            <div data-i18n="Manual Book">Manual Book</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Archive</span>
            </li>
            <li class="menu-item">
                <a href="{{ route('archive.quotation') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-delete-variant"></i>
                    <div data-i18n="Archive Quotation">Archive Quotation</div>
                </a>
            </li>
        @elseif (auth::user()?->role == 'Support')
            <!-- Dashboards -->
            <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                    <div data-i18n="Dashboards">Dashboards</div>
                </a>
            </li>
            {{-- <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-phone-incoming-outgoing-outline"></i>
                    <div data-i18n="Activities">Activities</div>
                </a>
            </li> --}}
            <li class="menu-item {{ request()->is('reports') ? 'active' : '' }}">
                <a href="{{ url('/reports') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-finance"></i>
                    <div data-i18n="Reports">Reports</div>
                </a>
            </li>
            <li
                class="menu-item {{ request()->is('overview') || request()->is('overview/*') || request()->is('overview/*/*') ? 'active' : '' }}">
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
                            <div data-i18n="Customers">Customers</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li
                class="menu-item {{ request()->is('quotation') || request()->is('quotation/*') || request()->is('po') || request()->is('loss') || request()->is('po/sales/*') || request()->is('quote/*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-email-outline"></i>
                    <div data-i18n="Quotation">Quotation</div>
                </a>
                <ul class="menu-sub">
                    <li
                        class="menu-item {{ request()->is('quotation') || request()->is('quotation/*') || request()->is('quotation/*') ? 'active' : '' }}">
                        <a href="{{ route('quotation.index') }}" class="menu-link">
                            <div data-i18n="Quotation">Quotation</div>
                        </a>
                    </li>
                    <li
                        class="menu-item {{ request()->is('quote/*') || request()->is('quote/*/*') ? 'active' : '' }}">
                        <a href="{{ route('index-unit.quotation') }}" class="menu-link">
                            <div data-i18n="Quotation Unit">Quotation Unit</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('po') ? 'active' : '' }}">
                        <a href="{{ route('quotation.po') }}" class="menu-link">
                            <div data-i18n="Done PO">Done PO</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('loss') ? 'active' : '' }}">
                        <a href="{{ route('quotation.loss') }}" class="menu-link">
                            <div data-i18n="Loss">Loss</div>
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
                <span class="menu-header-text">Prospect</span>
            </li>

            <li class="menu-item {{ request()->is('prospect') || request()->is('prospect/*') ? 'active' : '' }}">
                <a href="{{ route('prospect.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-details-outline"></i>
                    <div data-i18n="Prospect">Prospect</div>
                </a>
            </li>


            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Library</span>
            </li>
            <li class="menu-item {{ request()->is('library/index/*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-library-shelves"></i>
                    <div data-i18n="Library">Library</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('library/index/marktool') ? 'active' : '' }}">
                        <a href="{{ route('marktool.index') }}" class="menu-link">
                            <div data-i18n="Marketing Tools">Marketing Tools</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/brosur') ? 'active' : '' }}">
                        <a href="{{ route('brosur.index') }}" class="menu-link">
                            <div data-i18n="Brosur">Brosur</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/partlist') ? 'active' : '' }}">
                        <a href="{{ route('partlist.index') }}" class="menu-link">
                            <div data-i18n="Partlist">Partlist</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/manbook') ? 'active' : '' }}">
                        <a href="{{ route('manbook.index') }}" class="menu-link">
                            <div data-i18n="Manual Book">Manual Book</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Archive</span>
            </li>
            <li class="menu-item">
                <a href="{{ route('archive.quotation') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-delete-variant"></i>
                    <div data-i18n="Archive Quotation">Archive Quotation</div>
                </a>
            </li>
        @elseif(auth::user()?->role == 'Logistic')
            <li class="menu-item {{ request()->is('master/product') ? 'active' : '' }}">
                <a href="{{ route('master.product') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-package"></i>
                    <div data-i18n="Master">Master</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-package-variant"></i>
                    <div data-i18n="Product">Product</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('unit') || request()->is('unit/*') ? 'active' : '' }}">
                <a href="{{ route('unit.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-desktop-tower"></i>
                    <div data-i18n="Unit">Unit</div>
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
            <li class="menu-item {{ request()->is('return') || request()->is('return/*') ? 'active' : '' }}">
                <a href="{{ route('return.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-package-variant-closed-check"></i>
                    <div data-i18n="Return">Return</div>
                </a>
            </li>

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Invoice</span>
            </li>
            <li
                class="menu-item {{ request()->is('productout/invoice/*') || request()->is('productout/index/invoice') ? 'active' : '' }}">
                <a href="{{ route('product-out.index-invoice') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-book-open-outline"></i>
                    <div data-i18n="Invoice">Invoice</div>
                </a>
            </li>

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Notulen</span>
            </li>

            <li class="menu-item {{ request()->is('notulen') || request()->is('notulen/*') ? 'active' : '' }}">
                <a href="{{ route('notulen.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-box-outline"></i>
                    <div data-i18n="notulen">Notulen</div>
                    {{-- @if (@$leveledProspect >= 1)
                        <div class="badge bg-danger rounded-pill ms-auto">{{ $leveledProspect }}</div>
                    @endif --}}
                </a>
            </li>

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Library</span>
            </li>
            <li class="menu-item {{ request()->is('library/index/*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-library-shelves"></i>
                    <div data-i18n="Library">Library</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('library/index/marktool') ? 'active' : '' }}">
                        <a href="{{ route('marktool.index') }}" class="menu-link">
                            <div data-i18n="Marketing Tools">Marketing Tools</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/brosur') ? 'active' : '' }}">
                        <a href="{{ route('brosur.index') }}" class="menu-link">
                            <div data-i18n="Brosur">Brosur</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/partlist') ? 'active' : '' }}">
                        <a href="{{ route('partlist.index') }}" class="menu-link">
                            <div data-i18n="Partlist">Partlist</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/manbook') ? 'active' : '' }}">
                        <a href="{{ route('manbook.index') }}" class="menu-link">
                            <div data-i18n="Manual Book">Manual Book</div>
                        </a>
                    </li>
                </ul>
            </li>
        @elseif(auth::user()?->role == 'ServiceM')
            <!-- Dashboards -->
            <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                    <div data-i18n="Dashboards">Dashboards</div>
                </a>
            </li>
            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Monitoring</span>
            </li>
            <li
                class="menu-item {{ request()->is('service-manager') || request()->is('service-manager/*') ? 'active' : '' }}">
                <a href="{{ route('service-manager.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-file-chart-outline"></i>
                    <div data-i18n="Monitoring Fajar Paper">Monitoring Fajar Paper</div>
                </a>
            </li>

            {{-- <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Notulen</span>
            </li> --}}

            {{-- <li class="menu-item {{ request()->is('notulen') || request()->is('notulen/*') ? 'active' : '' }}">
                <a href="{{ route('notulen.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-box-outline"></i>
                    <div data-i18n="notulen">Notulen</div>
                </a>
            </li> --}}

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Library</span>
            </li>
            <li class="menu-item {{ request()->is('library/index/*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-library-shelves"></i>
                    <div data-i18n="Library">Library</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('library/index/marktool') ? 'active' : '' }}">
                        <a href="{{ route('marktool.index') }}" class="menu-link">
                            <div data-i18n="Marketing Tools">Marketing Tools</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/brosur') ? 'active' : '' }}">
                        <a href="{{ route('brosur.index') }}" class="menu-link">
                            <div data-i18n="Brosur">Brosur</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/partlist') ? 'active' : '' }}">
                        <a href="{{ route('partlist.index') }}" class="menu-link">
                            <div data-i18n="Partlist">Partlist</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/manbook') ? 'active' : '' }}">
                        <a href="{{ route('manbook.index') }}" class="menu-link">
                            <div data-i18n="Manual Book">Manual Book</div>
                        </a>
                    </li>
                </ul>
            </li>
        @elseif(auth::user()?->role == 'Technician' || auth::user()?->role == 'Coordinator')
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

            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Notulen</span>
            </li>

            <li class="menu-item {{ request()->is('notulen') || request()->is('notulen/*') ? 'active' : '' }}">
                <a href="{{ route('notulen.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-box-outline"></i>
                    <div data-i18n="notulen">Notulen</div>
                    {{-- @if (@$leveledProspect >= 1)
                        <div class="badge bg-danger rounded-pill ms-auto">{{ $leveledProspect }}</div>
                    @endif --}}
                </a>
            </li>


            <li class="menu-header fw-light mt-4">
                <span class="menu-header-text">Library</span>
            </li>
            <li class="menu-item {{ request()->is('library/index/*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-library-shelves"></i>
                    <div data-i18n="Library">Library</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('library/index/marktool') ? 'active' : '' }}">
                        <a href="{{ route('marktool.index') }}" class="menu-link">
                            <div data-i18n="Marketing Tools">Marketing Tools</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/brosur') ? 'active' : '' }}">
                        <a href="{{ route('brosur.index') }}" class="menu-link">
                            <div data-i18n="Brosur">Brosur</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/partlist') ? 'active' : '' }}">
                        <a href="{{ route('partlist.index') }}" class="menu-link">
                            <div data-i18n="Partlist">Partlist</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('library/index/manbook') ? 'active' : '' }}">
                        <a href="{{ route('manbook.index') }}" class="menu-link">
                            <div data-i18n="Manual Book">Manual Book</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

    </ul>
</aside>
