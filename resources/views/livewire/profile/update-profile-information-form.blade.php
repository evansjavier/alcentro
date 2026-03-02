<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="grid gap-5">
    <div class="space-y-2">
        <h2 class="kt-card-title">Informacion del perfil</h2>
        <p class="text-sm text-muted-foreground">
            Actualiza el nombre y el correo electronico de tu cuenta.
        </p>
    </div>

    <form wire:submit="updateProfileInformation" class="grid gap-5">
        <div class="grid gap-2">
            <x-input-label for="name" value="Nombre" />
            <x-text-input wire:model="name" id="name" name="name" type="text" required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div class="grid gap-2">
            <x-input-label for="email" value="Correo electronico" />
            <x-text-input wire:model="email" id="email" name="email" type="email" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 flex flex-col gap-2">
                    <span>Tu correo electronico no esta verificado.</span>

                    <div class="flex flex-wrap items-center gap-2">
                        <button
                            wire:click.prevent="sendVerification"
                            class="kt-btn kt-btn-outline kt-btn-sm"
                            type="button"
                        >
                            Reenviar correo de verificacion
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <span class="text-green-700 font-medium">Enlace enviado</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <x-primary-button>Guardar</x-primary-button>

            <x-action-message class="text-sm text-muted-foreground" on="profile-updated">
                Guardado
            </x-action-message>
        </div>
    </form>
</section>
