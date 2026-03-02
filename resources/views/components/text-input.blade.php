@props(['disabled' => false])

<input
	@disabled($disabled)
	{{ $attributes->merge(['class' => 'w-full rounded-lg border border-border bg-background px-4 py-2 text-sm text-foreground placeholder:text-muted-foreground shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition disabled:opacity-60 disabled:cursor-not-allowed']) }}
>
