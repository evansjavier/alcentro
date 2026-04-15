<div>
    @section('title', __('Usuarios'))

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
            <h3 class="text-base text-mono font-medium">Mostrando {{ $users->total() }} usuarios</h3>
            <span class="text-sm text-secondary-foreground">{{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} de {{ $users->total() }}</span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" class="kt-btn kt-btn-primary kt-btn-sm" wire:click.prevent="createUser">
                <i class="ki-outline ki-plus"></i> Nuevo Usuario
            </button>
        </div>
    </div>

    <div class="flex items-center flex-wrap gap-2.5 mb-4">
        <div class="flex">
            <label class="kt-input">
                <i class="ki-filled ki-magnifier"></i>
                <input wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre o correo..." type="search" class="w-64" />
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
                            <th class="px-4 py-3 font-medium">Correo Electrónico</th>
                            <th class="px-4 py-3 font-medium">Rol</th>
                            <th class="px-4 py-3 font-medium">Fecha Creación</th>
                            <th class="px-4 py-3 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-input">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-4 py-3 font-medium">
                                    {{ $user->name }}
                                    @if(auth()->id() === $user->id)
                                        <span class="kt-badge kt-badge-outline px-2 py-0.5 text-[10px] font-medium rounded-full bg-blue-500/10 text-blue-700 border-blue-200 ml-2">Tú</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    @if($user->hasRole(\App\Models\Role::ROLE_OWNER))
                                        <span class="kt-badge kt-badge-outline px-2.5 py-1 text-xs font-medium rounded-full bg-purple-500/10 text-purple-700 border-purple-200">Owner</span>
                                    @else
                                        <span class="kt-badge kt-badge-outline px-2.5 py-1 text-xs font-medium rounded-full bg-gray-500/10 text-gray-700 border-gray-200">Admin</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-secondary-foreground">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" class="kt-btn kt-btn-light kt-btn-sm" wire:click.prevent="editUser({{ $user->id }})">
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-secondary-foreground">
                                    No se encontraron usuarios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($users->hasPages())
            <div class="p-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Formulario -->
    <x-modal name="user-modal" maxWidth="md">
        <div class="">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-mono">
                    {{ $userId ? 'Editar Usuario' : 'Nuevo Usuario' }}
                </h3>
                <button type="button" @click="$dispatch('close-modal', 'user-modal')" class="text-secondary-foreground hover:text-primary transition-colors">
                    <i class="ki-filled ki-cross text-xl"></i>
                </button>
            </div>

            <form wire:submit="save" class="kt-modal-body space-y-4 p-0 pr-1 pb-2">
                <div class="space-y-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="block text-xs text-secondary-foreground font-medium mb-1" for="name">Nombre <span class="text-danger">*</span></label>
                        <input class="kt-input text-sm w-full" id="name" type="text" wire:model="name" required placeholder="Ej. Juan Pérez" />
                        @error('name') <span class="text-xs text-danger mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="block text-xs text-secondary-foreground font-medium mb-1" for="email">Correo Electrónico <span class="text-danger">*</span></label>
                        <input class="kt-input text-sm w-full" id="email" type="email" wire:model="email" required placeholder="ejemplo@correo.com" />
                        @error('email') <span class="text-xs text-danger mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="block text-xs text-secondary-foreground font-medium mb-1" for="password">Contraseña @if(!$userId)<span class="text-danger">*</span>@endif</label>
                        <input class="kt-input text-sm w-full" id="password" type="password" wire:model="password" {{ !$userId ? 'required' : '' }} placeholder="********" />
                        @if($userId)
                            <span class="text-xs text-muted-foreground mt-1">Déjalo en blanco si no deseas cambiar la contraseña.</span>
                        @endif
                        @error('password') <span class="text-xs text-danger mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="block text-xs text-secondary-foreground font-medium mb-1" for="role">Rol asignado <span class="text-danger">*</span></label>
                        <select class="kt-input text-sm w-full" id="role" wire:model="role" required>
                            @foreach($roles as $roleOption)
                                <option value="{{ $roleOption['id'] }}">{{ $roleOption['name'] }}</option>
                            @endforeach
                        </select>
                        @error('role') <span class="text-xs text-danger mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-5 pt-5 border-t border-input flex flex-wrap items-center justify-end gap-3">
                    <button type="button" @click="$dispatch('close-modal', 'user-modal')" class="kt-btn kt-btn-outline text-secondary-foreground">Cancelar</button>
                    <button type="submit" class="kt-btn kt-btn-primary">
                        <span wire:loading.remove wire:target="save">Guardar</span>
                        <span wire:loading wire:target="save" style="display: none;"><i class="ki-filled ki-loading animate-spin mr-2"></i> Guardando...</span>
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</div>