<div>
    @section('title', __('Conceptos de Gastos'))

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 border border-green-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="ki-filled ki-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="flex items-center justify-between gap-2.5 flex-wrap mb-7.5">
        <div class="flex flex-col">
            <h3 class="text-base text-mono font-medium">Mostrando {{ $concepts->total() }} conceptos</h3>
            <span class="text-sm text-secondary-foreground">{{ $concepts->firstItem() ?? 0 }}-{{ $concepts->lastItem() ?? 0 }} de {{ $concepts->total() }}</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('expenses.index') }}" class="kt-btn kt-btn-outline kt-btn-sm text-secondary-foreground">
                <i class="ki-outline ki-arrow-left"></i> Volver a Gastos
            </a>
            <button type="button" class="kt-btn kt-btn-primary kt-btn-sm" wire:click.prevent="createConcept">
                <i class="ki-outline ki-plus"></i> Nuevo Concepto
            </button>
        </div>
    </div>

    <div class="flex items-center flex-wrap gap-2.5 mb-4">
        <div class="flex">
            <label class="kt-input">
                <i class="ki-filled ki-magnifier"></i>
                <input wire:model.live.debounce.300ms="search" placeholder="Buscar concepto..." type="search" class="w-64" />
            </label>
        </div>
    </div>

    <div class="kt-card">
        <div class="kt-card-content p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left bg-muted/60">
                        <tr class="text-secondary-foreground">
                            <th class="px-4 py-3 font-medium">Nombre</th>
                            <th class="px-4 py-3 font-medium text-center">Estado</th>
                            <th class="px-4 py-3 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-input">
                        @forelse($concepts as $concept)
                            <tr>
                                <td class="px-4 py-3 font-medium">
                                    {{ $concept->name }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button
                                        wire:click="toggleActive({{ $concept->id }})"
                                        class="kt-badge kt-badge-outline px-2.5 py-1 text-xs font-medium rounded-full cursor-pointer {{ $concept->is_active ? 'bg-green-500/10 text-green-700 border-green-200' : 'bg-red-500/10 text-red-700 border-red-200' }}"
                                        title="Clic para cambiar estado"
                                    >
                                        {{ $concept->is_active ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" class="kt-btn kt-btn-light kt-btn-sm" wire:click.prevent="editConcept({{ $concept->id }})">
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-secondary-foreground">
                                    No se encontraron conceptos de gastos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($concepts->hasPages())
            <div class="p-4">
                {{ $concepts->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Formulario -->
    <x-modal name="concept-modal" maxWidth="md">
        <div class="">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-mono">
                    {{ $conceptId ? 'Editar Concepto' : 'Nuevo Concepto' }}
                </h3>
                <button type="button" @click="$dispatch('close-modal', 'concept-modal')" class="text-secondary-foreground hover:text-primary transition-colors">
                    <i class="ki-filled ki-cross text-xl"></i>
                </button>
            </div>

            <form wire:submit="save" class="kt-modal-body space-y-4 p-0 pr-1 pb-2">
                <div class="space-y-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="block text-xs text-secondary-foreground font-medium mb-1" for="name">Nombre <span class="text-danger">*</span></label>
                        <input class="kt-input text-sm w-full" id="name" type="text" wire:model="name" required placeholder="Nombre del concepto" />
                        @error('name') <span class="text-xs text-danger mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_active" value="1" class="rounded border-input text-primary focus:ring-primary h-4 w-4" />
                            <span class="block text-sm text-secondary-foreground font-medium">Concepto Activo</span>
                        </label>
                    </div>
                </div>

                <div class="mt-5 pt-5 border-t border-input flex flex-wrap items-center justify-end gap-3">
                    <button type="button" class="kt-btn kt-btn-outline text-secondary-foreground" @click="$dispatch('close-modal', 'concept-modal')">
                        Cancelar
                    </button>
                    <button type="submit" class="kt-btn kt-btn-primary" wire:loading.attr="disabled">
                        <i class="ki-filled ki-loading animate-spin" wire:loading wire:target="save" style="display: none;"></i>
                        <span wire:loading.remove wire:target="save">Guardar</span>
                        <span wire:loading wire:target="save">Guardando...</span>
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
