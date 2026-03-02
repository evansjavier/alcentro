@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-foreground mb-2']) }}>
    {{ $value ?? $slot }}
</label>
