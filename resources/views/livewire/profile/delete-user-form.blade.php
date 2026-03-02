<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="grid gap-5">
    <div class="space-y-2">
        <h2 class="kt-card-title">Eliminar cuenta</h2>
        <p class="text-sm text-muted-foreground">
            Al eliminar tu cuenta, todos los datos se borraran de forma permanente. Descarga primero la informacion que necesites conservar.
        </p>
    </div>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Eliminar cuenta</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6 space-y-4">

            <h2 class="text-lg font-semibold text-foreground">
                Estas seguro de eliminar tu cuenta?
            </h2>

            <p class="text-sm text-muted-foreground">
                Al eliminar tu cuenta, todos los datos se borraran permanentemente. Ingresa tu contrasena para confirmar.
            </p>

            <div class="grid gap-2">
                <x-input-label for="password" value="Contrasena" class="sr-only" />

                <x-text-input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="w-full sm:w-3/4"
                    placeholder="Contrasena"
                />

                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>

                <x-danger-button>
                    Eliminar cuenta
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
