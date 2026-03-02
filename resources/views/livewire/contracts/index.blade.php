<div>
    @section('title', __('Contratos'))

    <div class="flex items-center justify-between gap-2.5 flex-wrap mb-7.5">
        <div class="flex flex-col">
            <h3 class="text-base text-mono font-medium">Mostrando {{ $contracts->total() }} contratos</h3>
            <span class="text-sm text-secondary-foreground">{{ $contracts->firstItem() ?? 0 }}-{{ $contracts->lastItem() ?? 0 }} de {{ $contracts->total() }}</span>
        </div>
        <div class="flex items-center gap-2">
            <a class="kt-btn kt-btn-primary" href="{{ route('contracts.create') }}">Nuevo contrato</a>
        </div>
    </div>

    <div class="flex items-center flex-wrap gap-2.5 mb-4">
        <select wire:model.live="status" class="kt-input w-44">
            <option value="all">Todos los estados</option>
            <option value="activo">Activo</option>
            <option value="pendiente_firma">Pendiente de firma</option>
            <option value="finalizado">Finalizado</option>
            <option value="rescindido">Rescindido</option>
        </select>

        <select wire:model.live="sort" class="kt-input w-40">
            <option value="latest">Más recientes</option>
            <option value="oldest">Más antiguos</option>
        </select>

        <div class="flex">
            <label class="kt-input">
                <i class="ki-filled ki-magnifier"></i>
                <input wire:model.live.debounce.300ms="search" placeholder="Buscar empresa o local" type="search" />
            </label>
        </div>
    </div>

    <div class="kt-card">
        <div class="kt-card-content p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left bg-muted/60">
                        <tr class="text-secondary-foreground">
                            <th class="px-4 py-3 font-medium">Local</th>
                            <th class="px-4 py-3 font-medium">Empresa</th>
                            <th class="px-4 py-3 font-medium">Canon</th>
                            <th class="px-4 py-3 font-medium">Inicio</th>
                            <th class="px-4 py-3 font-medium">Fin</th>
                            <th class="px-4 py-3 font-medium">Estado</th>
                            <th class="px-4 py-3 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-input">
                        @forelse ($contracts as $contract)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-mono">{{ $contract->premise?->code ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $contract->client?->name ?? '—' }}</td>
                                <td class="px-4 py-3">${{ number_format((float) $contract->rent_amount, 0, '.', ',') }}</td>
                                <td class="px-4 py-3">{{ $contract->start_date?->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ $contract->end_date?->format('Y-m-d') ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusLabel = [
                                            'activo' => 'Activo',
                                            'pendiente_firma' => 'Pendiente de firma',
                                            'finalizado' => 'Finalizado',
                                            'rescindido' => 'Rescindido',
                                        ][$contract->status] ?? $contract->status;
                                        $statusColor = match ($contract->status) {
                                            'activo' => 'bg-green-500/10 text-green-700 border-green-200',
                                            'pendiente_firma' => 'bg-amber-500/10 text-amber-700 border-amber-200',
                                            'finalizado' => 'bg-slate-500/10 text-slate-700 border-slate-200',
                                            'rescindido' => 'bg-red-500/10 text-red-700 border-red-200',
                                            default => 'bg-muted text-foreground border-input',
                                        };
                                    @endphp
                                    <span class="kt-badge kt-badge-outline {{ $statusColor }} border px-2.5 py-1 text-xs font-medium rounded-full">{{ $statusLabel }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a class="kt-btn kt-btn-light kt-btn-sm" href="{{ route('contracts.show', $contract) }}">Ver contrato</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-secondary-foreground" colspan="7">No hay contratos registrados con los filtros actuales.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="p-4">
            {{ $contracts->onEachSide(1)->links() }}
        </div>
    </div>
</div>
