<div x-data="{ open: false, query: @entangle('query').live }"
     x-init="$watch('query', value => open = value.length > 0)"
     @keydown.window.prevent.cmd.slash="$refs.searchInput.focus()"
     @keydown.window.prevent.ctrl.slash="$refs.searchInput.focus()"
     class="relative">
    <div class="kt-input z-50">
        <i class="ki-filled ki-magnifier"></i>
        <input
            x-ref="searchInput"
            wire:model.live.debounce.300ms="query"
            class="min-w-0 bg-transparent"
            placeholder="Search"
            type="text"
            @focus="if($wire.query.length > 0) open = true"
            @click.away="open = false"
        >
        <span class="text-xs text-secondary-foreground text-nowrap">
            cmd + /
        </span>
    </div>

    @if(!empty($query))
        <div
            x-show="open"
            x-transition
            class="absolute bg-white border border-border rounded-xl shadow-lg mt-2 p-2" style="z-index: 50; width: 350px; max-height: 400px; overflow-y: auto;"
            style="display: none;"
        >
            @if($clients->isEmpty() && $premises->isEmpty() && $invoices->isEmpty() && $payments->isEmpty() && $contracts->isEmpty())
                <div class="p-2 text-sm text-gray-500">No se encontraron resultados</div>
            @endif

            @if($clients->isNotEmpty())
                <div class="mb-2">
                    <div class="px-2 py-1 text-xs font-bold uppercase text-gray-400">Clientes</div>
                    @foreach($clients as $client)
                        <a href="{{ route('clients.index', ['search' => $client->name]) }}" class="flex items-center px-2 py-1.5 hover:bg-gray-100 rounded-lg text-sm cursor-pointer gap-2">
                            <i class="ki-filled ki-profile-circle mr-2 text-gray-400"></i>
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-900">{{ $client->name }}</span>
                                <span class="text-xs text-gray-500">{{ $client->tax_id }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if($premises->isNotEmpty())
                <div class="mb-2">
                    <div class="px-2 py-1 text-xs font-bold uppercase text-gray-400">Locales</div>
                    @foreach($premises as $premise)
                        <a href="{{ route('premises.index', ['search' => $premise->code]) }}" class="flex items-center px-2 py-1.5 hover:bg-gray-100 rounded-lg text-sm cursor-pointer gap-2">
                            <i class="ki-filled ki-shop mr-2 text-gray-400"></i>
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-900">{{ $premise->code }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if($contracts->isNotEmpty())
                <div class="p-2">
                    <div class="px-2 pb-1 text-xs font-semibold text-gray-500 uppercase">Contratos Activos</div>
                    @foreach($contracts as $contract)
                        <a href="{{ route('contracts.index', ['search' => $contract->premise->code]) }}" class="block p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors text-sm flex items-center gap-2">
                            <i class="ki-duotone ki-document text-gray-400"></i>
                            <div>
                                <div class="font-medium">Local: {{ $contract->premise->code }}</div>
                                <div class="text-xs text-gray-500">Cliente: {{ $contract->client->name }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if($invoices->isNotEmpty())
                <div class="mb-2">
                    <div class="px-2 py-1 text-xs font-bold uppercase text-gray-400">Facturas</div>
                    @foreach($invoices as $invoice)
                        <a href="{{ route('invoices.show', $invoice) }}" class="flex items-center px-2 py-1.5 hover:bg-gray-100 rounded-lg text-sm cursor-pointer gap-2">
                            <i class="ki-filled ki-document mr-2 text-gray-400"></i>
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-900">#{{ $invoice->id }} - {{ $invoice->period }}</span>
                                <span class="text-xs text-gray-500">${{ number_format($invoice->total_amount, 2) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if($payments->isNotEmpty())
                <div class="mb-2">
                    <div class="px-2 py-1 text-xs font-bold uppercase text-gray-400">Pagos</div>
                    @foreach($payments as $payment)
                        <div class="flex items-center px-2 py-1.5 hover:bg-gray-100 rounded-lg text-sm cursor-pointer gap-2">
                            <i class="ki-filled ki-wallet mr-2 text-gray-400"></i>
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-900">{{ $payment->reference_number }}</span>
                                <span class="text-xs text-gray-500">${{ number_format($payment->amount_received, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>
