<!-- Toolbar -->
<div class="pb-5">
    <!-- Container -->
    <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center flex-wrap gap-1 lg:gap-5">
            @include('layouts.partials.page-title')
            <h1 class="font-medium text-lg text-mono">
                {{ $pageTitleResolved ?? __('Dashboard') }}
            </h1>
        </div>

    </div>
    <!-- End of Container -->
</div>
<!-- End of Toolbar -->
