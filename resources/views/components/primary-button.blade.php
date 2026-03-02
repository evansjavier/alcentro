<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'kt-btn kt-btn-primary disabled:opacity-60 disabled:cursor-not-allowed']) }}
>
    {{ $slot }}
</button>
