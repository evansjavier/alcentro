<div>
    @php
    use Illuminate\Support\Str;
    @endphp

    @section('title', __('Empresas'))


    <div class="flex items-center justify-between gap-2.5 flex-wrap mb-7.5">
        <div class="flex flex-col">
            <h3 class="text-base text-mono font-medium">Mostrando {{ $clients->total() }} empresas</h3>
            <span class="text-sm text-secondary-foreground">{{ $clients->firstItem() ?? 0 }}-{{ $clients->lastItem() ?? 0 }} de {{ $clients->total() }}</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('clients.create') }}" class="kt-btn kt-btn-primary">
                <i class="ki-filled ki-plus"></i>
                Nueva Empresa
            </a>
        </div>
    </div>

    <div class="flex items-center flex-wrap gap-2.5 mb-5">
        <select wire:model.live="status" class="kt-input w-40">
                <option value="all">Todos</option>
                <option value="with_email">Con correo</option>
                <option value="without_email">Sin correo</option>
            </select>
            <select wire:model.live="sort" class="kt-input w-36">
                <option value="latest">Más recientes</option>
                <option value="name">Alfabético</option>
                <option value="oldest">Más antiguos</option>
            </select>
            <div class="flex">
                <label class="kt-input">
                    <i class="ki-filled ki-magnifier"></i>
                    <input wire:model.live.debounce.300ms="search" placeholder="Buscar nombre, NIT, correo, teléfono" type="search" />
                </label>
            </div>
        </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 lg:gap-7.5">
        @forelse ($clients as $client)
            <div class="kt-card flex flex-col items-center p-5 lg:py-10">
                <div class="mb-3.5">
                    <div class="flex items-center justify-center relative text-2xl text-primary size-20 ring-1 ring-primary/20 bg-primary/5 rounded-full">
                        {{ Str::upper(Str::substr($client->name, 0, 1)) }}
                        <div class="flex size-2.5 {{ $client->email ? 'bg-green-500' : 'bg-violet-500' }} rounded-full absolute bottom-0.5 start-16 transform -translate-y-1/2"></div>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-1.5 mb-2">
                    <span class="hover:text-primary text-base leading-5 font-medium text-mono">{{ $client->name }}</span>
                </div>
                <div class="text-secondary-foreground text-sm hover:text-primary text-center">{{ $client->email ?: 'Sin correo' }}</div>
                <div class="text-sm text-muted-foreground mt-1 text-center">NIT/RUT: {{ $client->tax_id ?: 'No registrado' }}</div>
                <div class="text-sm text-muted-foreground text-center">Tel: {{ $client->phone ?: 'No registrado' }}</div>
                
                <div class="mt-4 pt-4 border-t border-input w-full flex justify-center">
                    <a href="{{ route('clients.edit', $client) }}" class="kt-btn kt-btn-light kt-btn-sm w-full">Editar</a>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 xl:col-span-4">
                <div class="kt-card p-6 text-center text-secondary-foreground">No se encontraron clientes con los filtros actuales.</div>
            </div>
        @endforelse
    </div>

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 pt-5 lg:pt-7.5">
        <div class="text-sm text-secondary-foreground">Página {{ $clients->currentPage() }} de {{ $clients->lastPage() }}</div>
        <div>
            {{ $clients->onEachSide(1)->links() }}
        </div>
    </div>

</div>
