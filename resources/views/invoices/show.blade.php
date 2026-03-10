@extends('layouts.app')

@section('title', __('Detalle de factura'))
@section('content')
    <div class="grid gap-5 lg:gap-7.5 xl:w-[48rem] mx-auto">
        @if (session('status'))
            <div class="kt-alert kt-alert-success">
                <div class="flex items-start gap-3">
                    <i class="ki-filled ki-check"></i>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold">{{ session('status') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex items-center justify-between gap-2">
            <a class="kt-btn kt-btn-light" href="{{ route('invoices.index') }}">
                <i class="ki-filled ki-arrow-left"></i>
                Volver
            </a>
            
            <div class="flex gap-2">
                @if($invoice->status !== 'paid')
                <a href="{{ route('invoices.payments.create', $invoice) }}" class="kt-btn kt-btn-primary">
                    <i class="ki-filled ki-wallet"></i>
                    Registrar Pago
                </a>
                @endif
            </div>
        </div>

        <div class="kt-card">
            <div class="kt-card-header">
                <h3 class="kt-card-title flex items-center gap-2">
                    Factura #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}
                    @php
                        $statusLabel = [
                            'pending' => 'Pendiente',
                            'partial' => 'Abono Parcial',
                            'paid' => 'Pagada',       
                        ][$invoice->status] ?? $invoice->status;
                        $statusColor = match ($invoice->status) {                                                                                                                          
                            'pending' => 'bg-amber-500/10 text-amber-700 border-amber-200',                                                                                                  
                            'partial' => 'bg-blue-500/10 text-blue-700 border-blue-200',                                                                                         
                            'paid' => 'bg-green-500/10 text-green-700 border-green-200',                                                                                                                                                                                                                               
                            default => 'bg-muted text-foreground border-input',                                                                                                         
                        };
                    @endphp
                    <span class="kt-badge kt-badge-outline {{ $statusColor }} border px-2.5 py-1 text-xs font-medium rounded-full ml-3">{{ $statusLabel }}</span>
                </h3>
            </div>
            <div class="kt-card-content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-input">
                    <div>
                        <span class="text-sm text-secondary-foreground block mb-1">Empresa / Inquilino</span>
                        <div class="font-semibold">{{ $invoice->client?->name ?? '—' }}</div>
                        <div class="text-sm text-muted-foreground">{{ $invoice->client?->tax_id }}</div>
                    </div>
                    <div>
                        <span class="text-sm text-secondary-foreground block mb-1">Periodo</span>
                        <div class="font-medium">{{ $invoice->period }}</div>
                        <div class="text-sm text-muted-foreground mt-1">Vence: {{ $invoice->due_date?->format('d/m/Y') }}</div>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="text-sm font-semibold mb-3">Detalle de conceptos facturados</h4>
                    <div class="border border-input rounded-lg overflow-hidden">
                        <table class="min-w-full text-sm">
                            <thead class="bg-muted/60">
                                <tr class="text-left text-secondary-foreground">
                                    <th class="px-4 py-2 font-medium">Concepto</th>
                                    <th class="px-4 py-2 font-medium text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-input">
                                @foreach($invoice->items as $item)
                                    <tr>
                                        <td class="px-4 py-2">
                                            <div class="font-medium">{{ $item->description }}</div>
                                            <div class="text-xs text-muted-foreground">Tipo: {{ $item->type_label }}</div>
                                        </td>
                                        <td class="px-4 py-2 text-right font-medium">
                                            ${{ number_format((float) $item->amount, 2, '.', ',') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-muted/30 rounded-lg p-5 border border-input">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-secondary-foreground">Monto Total Facturado</span>
                        <span class="font-semibold text-lg">${{ number_format((float) $invoice->total_amount, 2, '.', ',') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-secondary-foreground">Monto Pagado / Abonado</span>
                        <span class="font-medium text-green-600">${{ number_format((float) $invoice->paid_amount, 2, '.', ',') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-input">
                        <span class="font-semibold">Saldo Pendiente</span>
                        <span class="font-bold text-xl {{ $invoice->status === 'paid' ? 'text-green-600' : 'text-red-500' }}">
                            ${{ number_format((float) ($invoice->total_amount - $invoice->paid_amount), 2, '.', ',') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="kt-card">
            <div class="kt-card-header">
                <h3 class="kt-card-title">Historial de Pagos</h3>
            </div>
            <div class="kt-card-content p-0">
                <table class="min-w-full text-sm">
                    <thead class="bg-muted/60">
                        <tr class="text-secondary-foreground text-left">
                            <th class="px-5 py-3 font-medium">Fecha</th>
                            <th class="px-5 py-3 font-medium">Concepto</th>
                            <th class="px-5 py-3 font-medium text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-input">
                        @forelse($invoice->payments as $payment)
                            <tr>
                                <td class="px-5 py-3">{{ $payment->payment_date->format('d/m/Y') }}</td>
                                <td class="px-5 py-3">
                                    {{ $payment->method === 'cash' ? 'Efectivo' : 'Transferencia/Depósito' }}
                                    @if($payment->reference_number)
                                        <div class="text-xs text-muted-foreground mt-0.5">Ref: {{ $payment->reference_number }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right font-medium">${{ number_format((float) $payment->amount_received, 2, '.', ',') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-6 text-center text-muted-foreground">
                                    Aún no hay abonos ni pagos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
