<div>
    @section("title", isset($client) ? __("Editar Empresa") : __("Crear Empresa"))

    <form wire:submit="save" class="grid gap-5 lg:gap-7.5 xl:w-[48rem] mx-auto">
        <div class="flex items-center justify-between gap-2">
            <a class="kt-btn kt-btn-light" href="{{ route("clients.index") }}">
                <i class="ki-filled ki-arrow-left"></i>
                Volver
            </a>
            
            <button type="submit" class="kt-btn kt-btn-primary">
                <i class="ki-filled ki-check"></i>
                Guardar
            </button>
        </div>

        <div class="kt-card">
            <div class="kt-card-header">
                <h3 class="kt-card-title flex items-center gap-2">
                    {{ isset($client) ? "Editar Detalles" : "Detalles de la Empresa" }}
                </h3>
            </div>
            <div class="kt-card-content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium">Nombre / Razón Social <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" class="kt-input" required placeholder="Ej. Comercializadora Estrella" />
                        @error("name") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium">NIT / RUT</label>
                        <input type="text" wire:model="tax_id" class="kt-input" placeholder="Opcional" />
                        @error("tax_id") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium">Correo Electrónico</label>
                        <input type="email" wire:model="email" class="kt-input" placeholder="ejemplo@correo.com" />
                        @error("email") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium">Teléfono</label>
                        <input type="text" wire:model="phone" class="kt-input" placeholder="Opcional" />
                        @error("phone") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

