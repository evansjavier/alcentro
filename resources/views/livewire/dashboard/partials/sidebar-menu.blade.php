<div class="flex items-stretch grow shrink-0 justify-center my-5" id="sidebar_menu">
    <div class="kt-scrollable-y-auto grow" data-kt-scrollable="true" data-kt-scrollable-dependencies="#sidebar_header, #sidebar_footer" data-kt-scrollable-height="auto" data-kt-scrollable-offset="0px" data-kt-scrollable-wrappers="#sidebar_menu">
        <div class="kt-menu flex flex-col w-full gap-1.5 px-3.5" data-kt-menu="true" id="sidebar_primary_menu">

            <div class="kt-menu-item">
                <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent" href="{{ route('dashboard') }}">
                    <span class="kt-menu-icon items-start text-lg text-secondary-foreground">
                        <i class="ki-filled ki-home-1"></i>
                    </span>
                    <span class="kt-menu-title text-sm text-foreground font-medium">Dashboard</span>
                </a>
            </div>

            <div class="kt-menu-item">
                <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent" href="{{ route('clients.index') }}">
                    <span class="kt-menu-icon items-start text-lg text-secondary-foreground">
                        <i class="ki-filled ki-user"></i>
                    </span>
                    <span class="kt-menu-title text-sm text-foreground font-medium">Empresas</span>
                </a>
            </div>

            <div class="kt-menu-item">
                <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent" href="{{ route('premises.index') }}">
                    <span class="kt-menu-icon items-start text-lg text-secondary-foreground">
                        <i class="ki-filled ki-element-11"></i>
                    </span>
                    <span class="kt-menu-title text-sm text-foreground font-medium">Locales</span>
                </a>
            </div>

            <div class="kt-menu-item">
                <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent" href="{{ route('contracts.index') }}">
                    <span class="kt-menu-icon items-start text-lg text-secondary-foreground">
                        <i class="ki-filled ki-briefcase"></i>
                    </span>
                    <span class="kt-menu-title text-sm text-foreground font-medium">Contratos</span>
                </a>
            </div>

            <div class="kt-menu-item kt-menu-item-accordion" data-kt-menu-item-toggle="accordion" data-kt-menu-item-trigger="click">
                <div class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent">
                    <span class="kt-menu-icon items-start text-lg text-secondary-foreground">
                        <i class="ki-filled ki-wallet"></i>
                    </span>
                    <span class="kt-menu-title text-sm text-foreground font-medium">Facturacion</span>
                    <span class="kt-menu-arrow text-muted-foreground">
                        <i class="ki-filled ki-down text-xs"></i>
                    </span>
                </div>
                <div class="kt-menu-accordion gap-px ps-7">
                    <div class="kt-menu-item">
                        <a class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent {{ request()->routeIs('invoices.*') ? 'bg-secondary' : '' }}" href="{{ route('invoices.index') }}">
                            <span class="kt-menu-title text-sm text-foreground">Facturas</span>
                        </a>
                    </div>
                    <div class="kt-menu-item">
                        <a class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent {{ request()->routeIs('payments.*') ? 'bg-secondary' : '' }}" href="{{ route('payments.index') }}">
                            <span class="kt-menu-title text-sm text-foreground">Pagos</span>
                        </a>
                    </div>
                    <div class="kt-menu-item">
                        <div class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent">
                            <span class="kt-menu-title text-sm text-foreground">Reportes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
