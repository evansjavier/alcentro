<div>
    @section("title", __("Pagos Recibidos"))

    <div class="flex items-center justify-between gap-2.5 flex-wrap mb-7.5">
        <div class="flex flex-col">
            <h3 class="text-base text-mono font-medium">Mostrando {{ $payments->total() }} pagos</h3>
            <span class="text-sm text-secondary-foreground">{{ $payments->firstItem() ?? 0 }}-{{ $payments->lastItem() ?? 0 }} de {{ $payments->total() }}</span>
        </div>
        <div class="flex items-center gap-2">
            <!-- Espacio para botones -->
        </div>
    </div>

    <div class="flex items-center flex-wrap gap-2.5 mb-4">
        <select wire:model.live="method" class="kt-input w-44">
            <option value="all">Cualquier método</option>
            <option value="cash">Efectivo</option>
            <option value="wire_transfer">Transferencia / Depósito</option>
        </select>

        <div class="flex">
            <label class="kt-input text-secondary-foreground flex items-center">
                <i class="ki-filled ki-magnifier mr-3"></i>
                <input wire:model.live.debounce.300ms="search" placeholder="Cliente, periodo o ref..." type="search" class="w-64 border-0 focus:ring-0 p-0 !bg-transparent" />
            </label>
        </div>
    </div>

    <div class="kt-card">
        <div class="kt-card-content p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left bg-muted/60">
                        <tr class="text-secondary-foreground">
                            <th class="px-4 py-3 font-medium">Fecha</th>
                            <th class="px-4 py-3 font-medium">Factura / Periodo</th>
                            <th class="px-4 py-3 font-medium">Cliente</th>
                            <th class="px-4 py-3 font-medium">Método / Ref</th>
                            <th class="px-4 py-3 font-medium">Monto</th>
                            <th class="px-4 py-3 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-input">
                        @forelse ($payments as $payment)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $payment->payment_date->format("d/m/Y") }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-mono">#{{ str_pad($payment->invoice_id, 6, "0", STR_PAD_LEFT) }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $payment->invoice->period }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ $payment->invoice->client->name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $payment->invoice->client->tax_id }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-muted text-secondary-foreground border border-input">
                                        {{ $payment->method === "cash" ? "Efectivo" : "Transferencia" }}
                                    </span>
                                    @if($payment->reference_number)
                                        <div class="text-xs text-muted-foreground mt-1 text-mono">Ref: {{ $payment->reference_number }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-semibold text-green-600">${{ number_format((float) $payment->amount_received, 2, ".", ",") }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a class="kt-btn kt-btn-light kt-btn-sm" href="{{ route("invoices.show", $payment->invoice_id) }}">Ver factura</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-secondary-foreground" colspan="6">No hay pagos registrados que coincidan con la búsqueda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="p-4">
            {{ $payments->onEachSide(1)->links() }}
        </div>
    </div>
</div>

