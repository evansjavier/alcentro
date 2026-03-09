@extends('layouts.app')

@section('title', __('Finalizar contrato'))
@section('content')
    <div class="grid gap-5 lg:gap-7.5 xl:w-[44rem] mx-auto">
        <div class="flex items-center justify-between gap-2.5 flex-wrap">
            <div class="flex flex-col">
                <h3 class="text-base text-mono font-medium">{{ __('Finalizar Contrato') }}</h3>
                <span class="text-sm text-secondary-foreground">{{ $contract->client?->name }} • {{ $contract->premise?->code }}</span>
            </div>
            <div class="flex items-center gap-2">
                <a class="kt-btn kt-btn-outline" href="{{ route('contracts.show', $contract) }}">{{ __('Volver') }}</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="kt-alert kt-alert-destructive">
                <div class="flex items-start gap-3">
                    <i class="ki-filled ki-information"></i>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold">{{ __('Corrige los campos marcados para continuar.') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <form class="grid gap-5" id="contracts_terminate_form" method="POST" action="{{ route('contracts.processTermination', $contract) }}">
            @csrf

            <div class="kt-card pb-2.5">
                <div class="kt-card-header">
                    <h3 class="kt-card-title text-destructive">{{ __('Detalles de finalización') }}</h3>
                    <div class="flex items-center gap-2 text-sm text-secondary-foreground">
                        {{ __('Al finalizar, el local quedará disponible nuevamente.') }}
                    </div>
                </div>
                <div class="kt-card-content grid gap-5">

                    <div class="grid grid-cols-2 gap-4 bg-muted/30 p-4 rounded-lg outline outline-1 outline-border mb-2">
                        <div class="flex flex-col">
                            <span class="text-xs text-secondary-foreground">{{ __('Empresa') }}</span>
                            <span class="font-medium">{{ $contract->client?->name }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-secondary-foreground">{{ __('Local') }}</span>
                            <span class="font-medium">{{ $contract->premise?->code }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-secondary-foreground">{{ __('Canon mensual') }}</span>
                            <span class="font-medium">${{ number_format((float) $contract->rent_amount, 0, '.', ',') }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-secondary-foreground">{{ __('Fecha inicio') }}</span>
                            <span class="font-medium">{{ $contract->start_date?->format('Y-m-d') }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5 w-full">
                        <label class="kt-form-label" for="end_date">{{ __('Fecha de finalización efectiva') }}</label>
                        <input class="kt-input" id="end_date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" type="date">
                        @error('end_date')
                            <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-1.5 w-full">
                        <label class="kt-form-label" for="closing_note">{{ __('Nota de cierre (Opcional)') }}</label>
                        <textarea class="kt-input min-h-[120px] pt-2" id="closing_note" name="closing_note" placeholder="{{ __('Describe el motivo de la finalización y el estado de entrega del local (ej. mutuo acuerdo, rescisión por mora, deudas pendientes).') }}" rows="4">{{ old('closing_note') }}</textarea>
                        @error('closing_note')
                            <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <a class="kt-btn kt-btn-outline" href="{{ route('contracts.show', $contract) }}">
                    {{ __('Cancelar') }}
                </a>
                <button class="kt-btn kt-btn-danger" type="submit">
                    <span>{{ __('Confirmar Finalización') }}</span>
                </button>
            </div>
        </form>
    </div>
@endsection
