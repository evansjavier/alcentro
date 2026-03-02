<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'kt-btn kt-btn-destructive focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed']) }}
>
    {{ $slot }}
</button>
