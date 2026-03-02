<div>
    @section('title', 'Locales')

    <div class="flex items-center justify-between gap-2.5 flex-wrap mb-7.5">
        <div class="flex flex-col">
            <h3 class="text-base text-mono font-medium">Mostrando {{ $premises->total() }} locales</h3>
            <span class="text-sm text-secondary-foreground">{{ $premises->firstItem() ?? 0 }}-{{ $premises->lastItem() ?? 0 }} de {{ $premises->total() }}</span>
        </div>
        <div class="flex items-center flex-wrap gap-2.5">
            <a class="kt-btn kt-btn-primary" href="{{ route('premises.create') }}">Agregar local</a>
            <select wire:model.live="status" class="kt-input w-44">
                <option value="all">Todos</option>
                <option value="available">Disponibles</option>
                <option value="rented">Arrendados</option>
                <option value="maintenance">Mantenimiento</option>
            </select>
            <select wire:model.live="sort" class="kt-input w-48">
                <option value="latest">Más recientes</option>
                <option value="oldest">Más antiguos</option>
                <option value="code">Código A-Z</option>
                <option value="size_desc">Mayor área</option>
                <option value="rent_desc">Mayor canon</option>
            </select>
            <div class="flex">
                <label class="kt-input">
                    <i class="ki-filled ki-magnifier"></i>
                    <input wire:model.live.debounce.300ms="search" placeholder="Buscar código (LOC-101)" type="search" />
                </label>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 lg:gap-7.5">
        @forelse ($premises as $premise)
            <div class="kt-card relative flex flex-col gap-3 p-7">
                <div class="flex items-start justify-between gap-2 pr-10">
                    <div class="flex flex-col leading-tight">
                        <span class="text-xl font-semibold text-mono">{{ $premise->code }}</span>
                        @if ($premise->activeContract && $premise->activeContract->client)
                            <span class="text-sm text-secondary-foreground">{{ $premise->activeContract->client->name }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $statusColor = match ($premise->status) {
                                'available' => 'bg-green-500/10 text-green-600 border-green-200',
                                'rented' => 'bg-violet-500/10 text-violet-600 border-violet-200',
                                default => 'bg-amber-500/10 text-amber-600 border-amber-200',
                            };
                            $statusLabel = [
                                'available' => 'Disponible',
                                'rented' => 'Arrendado',
                                'maintenance' => 'Mantenimiento',
                            ][$premise->status] ?? $premise->status;
                        @endphp
                        <span class="kt-badge kt-badge-outline {{ $statusColor }} border px-2.5 py-1 text-xs font-medium rounded-full">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <div class="kt-menu absolute top-2" data-kt-menu="true" style="right: 2px;">
                    <div class="kt-menu-item kt-menu-item-dropdown" data-kt-menu-item-offset="0, 10px" data-kt-menu-item-placement="bottom-end" data-kt-menu-item-placement-rtl="bottom-start" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                        <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                            <i class="ki-filled ki-dots-vertical text-lg"></i>
                        </button>
                        <div class="kt-menu-dropdown kt-menu-default w-full max-w-[200px]" data-kt-menu-dismiss="true">
                            <div class="kt-menu-item">
                                <a class="kt-menu-link" href="{{ route('premises.edit', $premise) }}">
                                    <span class="kt-menu-icon">
                                        <i class="ki-filled ki-setting-3"></i>
                                    </span>
                                    <span class="kt-menu-title">Editar local</span>
                                </a>
                            </div>
                            @if ($premise->activeContract)
                                <div class="kt-menu-item">
                                    <a class="kt-menu-link" href="{{ route('contracts.show', $premise->activeContract) }}">
                                        <span class="kt-menu-icon">
                                            <i class="ki-filled ki-eye"></i>
                                        </span>
                                        <span class="kt-menu-title">Ver contrato</span>
                                    </a>
                                </div>
                                <div class="kt-menu-item">
                                    <a class="kt-menu-link" href="{{ route('contracts.create', ['premise' => $premise->id]) }}">
                                        <span class="kt-menu-icon">
                                            <i class="ki-filled ki-briefcase"></i>
                                        </span>
                                        <span class="kt-menu-title">Crear nuevo contrato</span>
                                    </a>
                                </div>
                            @else
                                <div class="kt-menu-item">
                                    <a class="kt-menu-link" href="{{ route('contracts.create', ['premise' => $premise->id]) }}">
                                        <span class="kt-menu-icon">
                                            <i class="ki-filled ki-briefcase"></i>
                                        </span>
                                        <span class="kt-menu-title">Asignar contrato</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-sm text-secondary-foreground">
                    <div class="flex items-center justify-between">
                        <span>Área</span>
                        <span class="font-medium">{{ number_format((float) $premise->square_meters, 2, '.', ',') }} m²</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Canon sugerido</span>
                        <span class="font-medium">${{ number_format((float) $premise->suggested_rent, 0, '.', ',') }}</span>
                    </div>
                </div>

                <div class="text-xs text-muted-foreground flex items-center justify-between">
                    <span>Actualizado</span>
                    <span>{{ $premise->updated_at?->diffForHumans() }}</span>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 xl:col-span-4">
                <div class="kt-card p-6 text-center text-secondary-foreground">No se encontraron locales con los filtros actuales.</div>
            </div>
        @endforelse
    </div>

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 pt-5 lg:pt-7.5">
        <div class="text-sm text-secondary-foreground">Página {{ $premises->currentPage() }} de {{ $premises->lastPage() }}</div>
        <div>
            {{ $premises->onEachSide(1)->links() }}
        </div>
    </div>
</div>
