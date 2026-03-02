<!DOCTYPE html>
<html class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="en">
    <head>
        @include('layouts.partials.head')
        <!-- Breeze auth bundle with Tailwind utilities, scoped to auth pages only -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="antialiased flex min-h-screen bg-muted text-base text-foreground">
        <div class="flex grow items-center justify-center px-4 py-8 lg:py-12">
            <div class="w-full max-w-xl">
                <div class="rounded-2xl border border-border bg-background shadow-sm">
                    <div class="px-8 pt-10 pb-2 text-center">
                        <a href="/demo6" class="inline-flex items-center justify-center">
                            <img class="h-10 dark:hidden" src="{{ asset('assets/app/logo.png') }}" alt="Logo" />
                            <img class="hidden h-10 dark:block" src="{{ asset('assets/app/logo.png') }}" alt="Logo" />
                        </a>
                        <h2 class="mt-4 text-2xl font-semibold">Bienvenido</h2>
                        <p class="mt-2 text-sm text-muted-foreground">Accede a tu cuenta para continuar.</p>
                    </div>
                    <div class="px-8 pb-10">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.partials.scripts')
        @livewireScripts
    </body>
</html>
