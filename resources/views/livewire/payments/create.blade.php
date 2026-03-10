<div>
    @section("title", __("Registrar Pago"))

    <form wire:submit="save" class="grid gap-5 lg:gap-7.5 xl:w-[48rem] mx-auto">
        <div class="flex items-center justify-between gap-2">
            <a class="kt-btn kt-btn-light" href="{{ route("invoices.show", $invoice) }}">
                <i class="ki-filled ki-arrow-left"></i>
                Volver
            </a>
            
            <button type="submit" class="kt-btn kt-btn-primary">
                <i class="ki-filled ki-check"></i>
                Guardar Pago
            </button>
        </div>

        <div class="kt-card">
            <div class="kt-card-header">
                <h3 class="kt-card-title flex items-center gap-2">
                    Detalles del Pago
                </h3>
            </div>
            <div class="kt-card-content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-input">
                    <div>
                        <span class="text-sm text-secondary-foreground block mb-1">Factura</span>
                        <div class="font-semibold">Factura #{{ str_pad($invoice->id, 6, "0", STR_PAD_LEFT) }}</div>
                        <div class="text-sm text-muted-foreground">{{ $invoice->client->name }}</div>
                    </div>
                    <div>
                        <span class="text-sm text-secondary-foreground block mb-1">Saldo Pendiente</span>
                        <div class="font-bold text-xl text-red-500">
                            ${{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium">Monto a pagar ($)</label>
                        <input type="number" step="0.01" max="{{ $invoice->total_amount - $invoice->paid_amount }}" wire:model="amount_received" class="kt-input" required />
                        @error("amount_received") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium">Fecha de pago</label>
                        <input type="date" wire:model="payment_date" class="kt-input" required />
                        @error("payment_date") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium">Método de pago</label>
                        <select wire:model="method" class="kt-input" required>
                            <option value="wire_transfer">Transferencia / Depósito</option>
                            <option value="cash">Efectivo</option>
                        </select>
                        @error("method") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium">Número de Referencia (opcional)</label>
                        <input type="text" wire:model="reference_number" class="kt-input" placeholder="Ej. 123456789" />
                        @error("reference_number") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">Notas u Observaciones (opcional)</label>
                    <textarea wire:model="notes" class="kt-input" rows="3" placeholder="Detalles adicionales del pago..."></textarea>
                    @error("notes") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </form>
</div>

