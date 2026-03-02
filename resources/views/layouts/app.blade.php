<!DOCTYPE html>
<html class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="en">
    <head>
        @include('layouts.partials.head')
        @livewireStyles
    </head>
    <body class="antialiased flex h-full text-base text-foreground bg-background [--header-height:60px] [--sidebar-width:270px] lg:overflow-hidden bg-muted">
        <livewire:shared.theme-toggle />

        <div class="flex grow">
            <livewire:dashboard.header />
            <livewire:dashboard.sidebar />

            <div class="flex flex-col lg:flex-row grow pt-(--header-height) lg:pt-0">
                <div class="flex flex-col grow items-stretch rounded-xl bg-background border border-input lg:ms-(--sidebar-width) mt-0 lg:mt-[15px] m-[15px]">
                    <div class="flex flex-col grow kt-scrollable-y-auto [--kt-scrollbar-width:auto] pt-5" id="scrollable_content">
                        <main class="grow" role="content">
                            <livewire:dashboard.toolbar />

                            <div class="kt-container-fixed">
                                @hasSection('content')
                                    @yield('content')
                                @elseif(isset($slot))
                                    {{ $slot }}
                                @endif
                            </div>
                        </main>
                        <livewire:dashboard.footer />
                    </div>
                </div>
            </div>
        </div>

        @stack('modals')
        @include('layouts.partials.scripts')
        @stack('scripts')
        @livewireScripts
    </body>
</html>
