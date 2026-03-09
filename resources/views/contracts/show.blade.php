@extends('layouts.app')

@section('title', __('Contrato'))
@section('content')
    <div class="grid gap-5 lg:gap-7.5 xl:w-[50rem] mx-auto">
        <div class="flex items-center justify-between gap-2.5 flex-wrap">
            <div class="flex flex-col">
                <h3 class="text-base text-mono font-medium">{{ __('Contrato de arrendamiento') }}</h3>
                <span class="text-sm text-secondary-foreground">{{ $contract->created_at?->format('Y-m-d H:i') }}</span>
            </div>
            <div class="flex items-center gap-2">
                @if(in_array($contract->status, ['activo', 'pendiente_firma']))
                    <a class="kt-btn kt-btn-danger kt-btn-outline" href="{{ route('contracts.terminate', $contract) }}">
                        {{ __('Finalizar Contrato') }}
                    </a>
                @endif
                <a class="kt-btn kt-btn-outline" href="{{ route('contracts.index') }}">{{ __('Volver') }}</a>
            </div>
        </div>

        @if (session('status'))
            <div class="kt-alert kt-alert-success mt-4">
                <div class="flex items-start gap-3">
                    <i class="ki-filled ki-check"></i>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold">{{ session('status') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <div class="kt-card mt-5">
            <div class="kt-card-content p-6 grid gap-4">
                <div class="flex flex-col gap-1">
                    <span class="text-sm text-secondary-foreground">{{ __('Empresa') }}</span>
                    <span class="text-base font-semibold">{{ $contract->client?->name ?? '—' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-sm text-secondary-foreground">{{ __('Local') }}</span>
                    <span class="text-base font-semibold">{{ $contract->premise?->code ?? '—' }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="flex flex-col gap-1">
                        <span class="text-sm text-secondary-foreground">{{ __('Canon mensual') }}</span>
                        <span class="text-base font-semibold">${{ number_format((float) $contract->rent_amount, 0, '.', ',') }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-sm text-secondary-foreground">{{ __('Día de pago') }}</span>
                        <span class="text-base">{{ $contract->payment_day }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-sm text-secondary-foreground">{{ __('Estado') }}</span>
                        @php
                            $statusLabel = [
                                'activo' => __('Activo'),
                                'pendiente_firma' => __('Pendiente de firma'),
                                'finalizado' => __('Finalizado'),
                                'rescindido' => __('Rescindido'),
                            ][$contract->status] ?? $contract->status;
                        @endphp
                        <span class="kt-badge kt-badge-outline border px-2.5 py-1 text-xs font-medium rounded-full">{{ $statusLabel }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <span class="text-sm text-secondary-foreground">{{ __('% Mantenimiento') }}</span>
                        <span class="text-base">{{ number_format((float) $contract->maintenance_pct, 2, '.', ',') }}%</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-sm text-secondary-foreground">{{ __('% Publicidad') }}</span>
                        <span class="text-base">{{ number_format((float) $contract->advertising_pct, 2, '.', ',') }}%</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <span class="text-sm text-secondary-foreground">{{ __('Fecha inicio') }}</span>
                        <span class="text-base">{{ $contract->start_date?->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-sm text-secondary-foreground">{{ __('Fecha fin') }}</span>
                        <span class="text-base">{{ $contract->end_date?->format('Y-m-d') ?? '—' }}</span>
                    </div>
                </div>

                @if ($contract->notes)
                    <div class="flex flex-col gap-1 mt-3">
                        <span class="text-sm text-secondary-foreground">{{ __('Notas') }}</span>
                        <div class="text-base leading-relaxed whitespace-pre-line">{{ $contract->notes }}</div>
                    </div>
                @endif

                @if ($contract->closing_note || $contract->closed_at)
                    <div class="pt-4 mt-4 border-t border-gray-200 grid gap-4">
                        <h4 class="text-sm font-semibold">{{ __('Información de finalización') }}</h4>
                        @if ($contract->closed_at)
                            <div class="flex flex-col gap-1">
                                <span class="text-sm text-secondary-foreground">{{ __('Fecha de registro de cierre') }}</span>
                                <span class="text-base">{{ $contract->closed_at->format('Y-m-d H:i:s') }}</span>
                            </div>
                        @endif
                        @if ($contract->closing_note)
                            <div class="flex flex-col gap-1">
                                <span class="text-sm text-secondary-foreground">{{ __('Nota de cierre') }}</span>
                                <div class="text-base leading-relaxed whitespace-pre-line">{{ $contract->closing_note }}</div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
