<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'kt-btn kt-btn-outline disabled:opacity-60 disabled:cursor-not-allowed']) }}
>
    {{ $slot }}
</button>
