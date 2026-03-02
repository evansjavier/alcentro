@php
    $sectionTitle = trim(View::getSection('title') ?? '');
    $pageTitleResolved = $pageTitle
        ?? ($sectionTitle !== '' ? $sectionTitle : null)
        ?? (Route::currentRouteName() ? \Illuminate\Support\Str::headline(Route::currentRouteName()) : __('Dashboard'));

    // Share with parent views so includes can read it.
    View::share('pageTitleResolved', $pageTitleResolved);
@endphp
