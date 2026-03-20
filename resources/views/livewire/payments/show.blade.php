<div>
    @section("title", __("Detalles del Pago"))

    <div class="grid gap-5 lg:gap-7.5 xl:w-[48rem] mx-auto">
        <div class="flex items-center justify-end gap-2">
            @if(!$payment->is_approved)
                <a href="{{ route('payments.edit', $payment) }}" class="kt-btn kt-btn-light">
                    <i class="ki-filled ki-pencil"></i>
                    Editar
                </a>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'approval-modal')" class="kt-btn kt-btn-success">
                    <i class="ki-filled ki-check-circle"></i>
                    Aprobar Pago
                </button>
            @endif
        </div>

        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-4 rounded-lg flex items-center gap-2 mb-4 text-sm font-medium border border-red-100">
                <i class="ki-filled ki-information-2 text-xl"></i>
                {{ session('error') }}
            </div>
        @endif
        @if(session('status'))
            <div class="bg-green-50 text-green-700 p-4 rounded-lg flex items-center gap-2 mb-4 text-sm font-medium border border-green-100">
                <i class="ki-filled ki-check-circle text-xl"></i>
                {{ session('status') }}
            </div>
        @endif

        <div class="kt-card">
            <div class="kt-card-header">
                <h3 class="kt-card-title flex items-center gap-2">
                    Detalles del Pago
                    @if($payment->is_approved)
                        <span class="kt-badge kt-badge-outline px-2 py-1 text-xs font-medium rounded-full bg-green-500/10 text-green-700 border-green-200">Aprobado</span>
                    @else
                        <span class="kt-badge kt-badge-outline px-2 py-1 text-xs font-medium rounded-full bg-yellow-500/10 text-yellow-700 border-yellow-200">Pendiente</span>
                    @endif
                </h3>
            </div>
            <div class="kt-card-content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-input">
                    <div>
                        <span class="text-sm text-secondary-foreground block mb-1">Factura Asociada</span>
                        <div class="font-semibold">Factura #{{ str_pad($payment->invoice->id, 6, "0", STR_PAD_LEFT) }}</div>
                        <div class="text-sm text-muted-foreground">{{ $payment->invoice->client->name }}</div>
                    </div>
                    <div>
                        <span class="text-sm text-secondary-foreground block mb-1">Monto del Pago</span>
                        <div class="font-bold text-xl text-primary">
                            ${{ number_format($payment->amount_received, 2) }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-6 pt-6">
                    <div>
                        <span class="text-sm text-secondary-foreground block mb-1">Fecha de Pago</span>
                        <div class="font-medium">{{ $payment->payment_date->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <span class="text-sm text-secondary-foreground block mb-1">Método de Pago</span>
                        <div class="font-medium">
                            {{ $payment->method === 'cash' ? 'Efectivo' : 'Transferencia / Depósito' }}
                        </div>
                    </div>
                    <div>
                        <span class="text-sm text-secondary-foreground block mb-1">Número de Referencia</span>
                        <div class="font-medium">
                            {{ $payment->reference_number ?: 'N/A' }}
                        </div>
                    </div>
                    @if($payment->is_approved && $payment->approved_at)
                    <div>
                        <span class="text-sm text-secondary-foreground block mb-1">Fecha de Aprobación</span>
                        <div class="font-medium text-green-700">
                            {{ $payment->approved_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    @endif
                    <div class="col-span-1 md:col-span-2">
                        <span class="text-sm text-secondary-foreground block mb-1">Notas / Observaciones</span>
                        <div class="font-medium bg-muted/60 p-4 rounded-lg text-sm min-h-[4rem] border border-input">
                            {{ $payment->notes ?: 'Sin notas adicionales.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <x-modal name="approval-modal" maxWidth="md">
        <div class="">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-mono">Confirmar Aprobación</h3>
                <button type="button" @click="$dispatch('close-modal', 'approval-modal')" class="text-secondary-foreground hover:text-primary transition-colors">
                    <i class="ki-filled ki-cross text-xl"></i>
                </button>
            </div>

            <div class="kt-modal-body space-y-4 p-0 pr-1 pb-2">
                <p class="text-muted-foreground text-sm">
                    ¿Estás seguro de que deseas aprobar este pago por <strong class="text-foreground">${{ number_format($payment->amount_received, 2) }}</strong>?
                </p>
                <div class="bg-yellow-50 text-yellow-800 p-3 rounded text-xs flex gap-2">
                    <i class="ki-filled ki-warning text-base mt-0.5"></i>
                    <span>Una vez aprobado, este monto se sumará al total pagado de la factura y no podrá ser editado.</span>
                </div>
            </div>
            
            <div class="mt-5 pt-5 border-t border-input flex flex-wrap items-center justify-end gap-3">
                <button type="button" @click="$dispatch('close-modal', 'approval-modal')" class="kt-btn kt-btn-outline text-secondary-foreground">Cancelar</button>
                <button wire:click="approvePayment" wire:loading.attr="disabled" class="kt-btn kt-btn-success">
                    <span wire:loading.remove wire:target="approvePayment">Sí, Aprobar Pago</span>
                    <span wire:loading wire:target="approvePayment" style="display: none;">
                        <i class="ki-filled ki-loading animate-spin mr-2"></i> Procesando
                    </span>
                </button>
            </div>
        </div>
    </x-modal>
</div>
