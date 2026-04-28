<div>
    @section('title', __('Conceptos de Operación'))

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 border border-green-200">
            <div class="flex">
                <div class="flex-shrink-0 pr-2">
                    <i class="ki-filled ki-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="mb-5 rounded-md kt-badge-outline kt-badge-info p-4 border">
        <div class="flex">
            <div class="flex-shrink-0 pr-2">
                <i class="ki-filled ki-information text-blue-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-800">
                    <strong class="font-medium">Nota:</strong> Estos conceptos se utilizan tanto como <strong>servicios asociables al contrato</strong> como para la clasificación de los <strong>gastos operativos</strong>.
                </p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between gap-2.5 flex-wrap mb-7.5">
        <div class="flex flex-col">
            <h3 class="text-base text-mono font-medium">Mostrando {{ $concepts->total() }} conceptos</h3>
            <span class="text-sm text-secondary-foreground">{{ $concepts->firstItem() ?? 0 }}-{{ $concepts->lastItem() ?? 0 }} de {{ $concepts->total() }}</span>
        </div>
        <div class="flex items-center gap-2">
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
                            <th class="px-4 py-3 font-medium text-center">Facturable</th>
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
                                        class="kt-badge kt-badge-outline px-2.5 py-1 text-xs font-medium rounded-full {{ $concept->is_billable ? 'kt-badge-primary' : '' }}"
                                        title="Clic para cambiar si es facturable"
                                    >
                                        {{ $concept->is_billable ? $concept->billing_period_months . ' ' . ($concept->billing_period_months == 1 ? 'mes' : 'meses') : 'No' }}
                                    </button>
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
                                <td colspan="4" class="px-4 py-6 text-center text-secondary-foreground">
                                    No se encontraron conceptos de operación registrados.
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

                    <div class="flex items-center gap-2 pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model.live="is_billable" value="1" class="rounded border-input text-primary focus:ring-primary h-4 w-4" />
                            <span class="block text-sm text-secondary-foreground font-medium">Concepto Facturable (Servicios a Arrendatarios)</span>
                        </label>
                    </div>

                    @if($is_billable)
                    <div class="flex flex-col gap-1.5 pt-2">
                        <label class="block text-xs text-secondary-foreground font-medium mb-1" for="billing_period_months">Periodo de Facturación (en meses) <span class="text-danger">*</span></label>
                        <select class="kt-input text-sm w-full" id="billing_period_months" wire:model="billing_period_months" required>
                            <option value="1">Mensual (1 mes)</option>
                            <option value="2">Bimestral (2 meses)</option>
                            <option value="3">Trimestral (3 meses)</option>
                            <option value="6">Semestral (6 meses)</option>
                            <option value="12">Anual (12 meses)</option>
                        </select>
                        <p class="text-xs text-muted-foreground mt-1">Indique cada cuántos meses se le debe cobrar este servicio al inquilino.</p>
                        @error('billing_period_months') <span class="text-xs text-danger mt-1">{{ $message }}</span> @enderror
                    </div>
                    @endif
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
