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

    <div class="grid grid-cols-1 gap-5 lg:gap-7.5">
        @forelse ($clients as $client)
            <div class="kt-card p-5">
                <div class="flex flex-wrap items-center justify-between gap-4 {{ $client->contacts->count() > 0 ? 'mb-4 pb-4 border-b border-gray-200 dark:border-gray-800' : '' }}">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center relative text-xl text-violet-500 size-10 ring-1 ring-violet-200 dark:ring-violet-950 bg-violet-50 dark:bg-violet-950/30 rounded-full">
                            {{ Str::upper(Str::substr($client->name, 0, 1)) }}
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <span class="text-lg font-medium text-mono">{{ $client->name }}</span>
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="text-sm text-secondary-foreground"><i class="ki-solid ki-sms text-gray-400 align-middle mr-1"></i>{{ $client->email ?: 'Sin correo' }}</span>
                                <span class="text-sm text-secondary-foreground"><i class="ki-solid ki-call text-gray-400 align-middle mr-1"></i>{{ $client->phone ?: 'No registrado' }}</span>
                                <span class="text-sm text-secondary-foreground">NIT: <span class="font-medium text-foreground">{{ $client->tax_id ?: 'N/A' }}</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="$dispatch('open-contacts-modal', { clientId: {{ $client->id }}, clientName: '{{ addslashes($client->name) }}' })" class="kt-btn kt-btn-outline kt-btn-sm text-secondary-foreground"><i class="ki-filled ki-people"></i> Contactos</button>
                        <a href="{{ route('clients.edit', $client) }}" class="kt-btn kt-btn-light kt-btn-sm"><i class="ki-filled ki-pencil"></i> Editar</a>
                    </div>
                </div>

                @if($client->contacts->count() > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                        @foreach($client->contacts as $contact)
                            <div class="kt-card border border-gray-100 shadow-none bg-secondary/5">
                                <div class="kt-card-content flex items-center flex-wrap justify-between p-2 pe-4 gap-4.5">
                                    <div class="flex items-center gap-3.5">
                                        <div class="flex items-center justify-center bg-accent/50 h-[50px] w-[50px] rounded-lg shadow-none shrink-0">
                                            @if($contact->role === 'Dueño')
                                                <i class="ki-filled ki-crown text-2xl text-yellow-500"></i>
                                            @elseif($contact->role === 'Encargado')
                                                <i class="ki-filled ki-user-tick text-2xl text-blue-500"></i>
                                            @elseif($contact->role === 'Fiador')
                                                <i class="ki-filled ki-shield-tick text-2xl text-emerald-500"></i>
                                            @else
                                                <i class="ki-filled ki-user text-2xl text-gray-500"></i>
                                            @endif
                                        </div>
                                        <div class="flex flex-col gap-1.5">
                                            <div class="flex items-center gap-2.5 -mt-1">
                                                <span class="text-sm font-medium text-mono leading-5.5">{{ $contact->name }}</span>
                                            </div>
                                            <div class="flex items-center flex-wrap gap-3">
                                                <span class="kt-badge kt-badge-light kt-badge-sm rounded-full">
                                                    {{ $contact->role ?: 'Contacto' }}
                                                </span>
                                                <div class="flex items-center flex-wrap gap-2 lg:gap-4">
                                                    @if($contact->phone)
                                                        <span class="text-xs font-normal text-secondary-foreground">
                                                            <i class="ki-solid ki-call text-gray-400 align-middle"></i>
                                                            <span class="text-xs font-medium text-foreground ml-0.5">
                                                                {{ $contact->phone }}
                                                            </span>
                                                        </span>
                                                    @endif
                                                    @if($contact->email)
                                                        <span class="text-xs font-normal text-secondary-foreground">
                                                            <i class="ki-solid ki-sms text-gray-400 align-middle"></i>
                                                            <span class="text-xs font-medium text-foreground ml-0.5">
                                                                {{ $contact->email }}
                                                            </span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        @if($contact->phone)

                                        <a href="tel:{{ $contact->phone }}" class="kt-btn kt-btn-icon kt-btn-light kt-btn-sm rounded-full bg-white text-primary border border-gray-200" title="Llamar">
                                                <i class="ki-solid ki-call text-lg"></i>
                                            </a>
                                            <a href="https://wa.me/57{{ $contact->phone }}" target="_blank" class="kt-btn kt-btn-icon kt-btn-light kt-btn-sm rounded-full bg-white text-green-600 hover:text-green-700 border border-gray-200" title="WhatsApp">
                                                <i class="ki-solid ki-whatsapp text-lg"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-1">
                <div class="kt-card p-6 text-center text-secondary-foreground">No se encontraron empresas con los filtros actuales.</div>
            </div>
        @endforelse
    </div>

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 pt-5 lg:pt-7.5">
        <div class="text-sm text-secondary-foreground">Página {{ $clients->currentPage() }} de {{ $clients->lastPage() }}</div>
        <div>
            {{ $clients->onEachSide(1)->links() }}
        </div>
    </div>

    <!-- Contactos Modal -->
    <div x-data="clientsContactsManager">
        <x-modal name="contacts-modal" maxWidth="2xl">
            <div class="">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-mono" x-text="'Contactos de ' + clientName"></h3>
                    <button type="button" @click="$dispatch('close-modal', 'contacts-modal')" class="text-secondary-foreground hover:text-primary transition-colors">
                        <i class="ki-filled ki-cross text-xl"></i>
                    </button>
                </div>

                <div class="kt-modal-body space-y-4 p-0 pr-1 pb-2" style="max-height: calc(70vh - 50px); overflow-y: auto;">
                    <template x-if="contacts.length === 0">
                        <div class="text-center text-secondary-foreground py-4">No hay contactos agregados.</div>
                    </template>

                    <template x-for="(contact, index) in contacts" :key="index">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end pt-5 pb-4 px-4 mt-2 border border-input rounded-xl relative bg-secondary/10">

                            <button @click="removeContact(index)" type="button" class="kt-btn kt-btn-icon kt-btn-outline kt-btn-sm text-danger hover:bg-danger-light" style="position: absolute; top: 6px; right: 10px;" title="Eliminar este contacto">
                                <i class="ki-filled ki-trash"></i>
                            </button>

                            <div class="sm:col-span-1 pt-3 sm:pt-0">
                                <label class="block text-xs text-secondary-foreground font-medium mb-1">Nombre <span class="text-danger">*</span></label>
                                <input x-model="contact.name" type="text" class="kt-input text-sm w-full" placeholder="Nombre completo">
                            </div>

                            <div class="sm:col-span-1">
                                <label class="block text-xs text-secondary-foreground font-medium mb-1">Teléfono <span class="text-danger">*</span></label>
                                <input x-model="contact.phone" type="text" maxlength="10"
                                    @input="contact.phone = $event.target.value.replace(/\D/g, '').substring(0, 10)"
                                    class="kt-input text-sm w-full" placeholder="10 dígitos">
                            </div>

                            <div class="sm:col-span-1">
                                <label class="block text-xs text-secondary-foreground font-medium mb-1">Rol</label>
                                <select x-model="contact.role" class="kt-input text-sm w-full" @change="checkRoles()">
                                    <option value="Dueño">Dueño</option>
                                    <option value="Encargado">Encargado</option>
                                    <option value="Fiador">Fiador</option>
                                </select>
                            </div>

                            <div class="sm:col-span-1">
                                <label class="block text-xs text-secondary-foreground font-medium mb-1">Correo</label>
                                <input x-model="contact.email" type="email" class="kt-input text-sm w-full" placeholder="Opcional">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs text-secondary-foreground font-medium mb-1">Notas</label>
                                <input x-model="contact.notes" type="text" class="kt-input text-sm w-full" placeholder="Información adicional (opcional)">
                            </div>

                            <div class="sm:col-span-2" x-show="errorMessage(index)">
                                <span class="text-xs text-danger" x-text="errorMessage(index)"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="ownerError" class="text-danger text-sm mt-3 flex items-center gap-1">
                    <i class="ki-filled ki-warning text-lg"></i> Sólo puede haber un contacto como Dueño.
                </div>

                <div class="mt-5 pt-5 border-t border-input flex flex-wrap items-center justify-between gap-3">
                    <button @click="addContact()" type="button" class="kt-btn kt-btn-light kt-btn-sm shrink-0">
                        <i class="ki-filled ki-plus"></i> Añadir Contacto
                    </button>

                    <div class="flex flex-wrap gap-2 shrink-0">
                        <button type="button" @click="$dispatch('close-modal', 'contacts-modal')" class="kt-btn kt-btn-outline text-secondary-foreground">Cancelar</button>
                        <button type="button" @click="save()" class="kt-btn kt-btn-primary" :disabled="saving">
                            <i class="ki-filled ki-loading animate-spin" x-show="saving" style="display: none;"></i>
                            <span x-text="saving ? 'Guardando...' : 'Guardar Contactos'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </x-modal>
    </div>

    @script
    <script>
        Alpine.data('clientsContactsManager', () => ({
            clientId: null,
            clientName: '',
            contacts: [],
            saving: false,
            ownerError: false,

            init() {
                window.addEventListener('open-contacts-modal', async (e) => {
                    this.clientId = e.detail.clientId;
                    this.clientName = e.detail.clientName;
                    this.contacts = [];
                    this.ownerError = false;

                    // Show modal first for faster UI response
                    this.$dispatch('open-modal', 'contacts-modal');

                    try {
                        let existing = await this.$wire.getContacts(this.clientId);
                        if(existing && existing.length > 0) {
                            this.contacts = existing.map(c => ({
                                id: c.id,
                                name: c.name || '',
                                phone: c.phone || '',
                                role: c.role || 'Encargado',
                                email: c.email || '',
                                notes: c.notes || ''
                            }));
                        } else {
                            this.addContact();
                        }
                    } catch (err) {
                        console.error("Error cargando contactos", err);
                        this.addContact();
                    }
                });

                window.addEventListener('contacts-saved', () => {
                    this.saving = false;
                    this.$dispatch('close-modal', 'contacts-modal');
                });
            },

            addContact() {
                this.contacts.push({
                    id: Date.now(),
                    name: '',
                    phone: '',
                    role: 'Encargado',
                    email: '',
                    notes: ''
                });
                this.checkRoles();
            },

            removeContact(index) {
                this.contacts.splice(index, 1);
                this.checkRoles();
            },

            checkRoles() {
                let owners = this.contacts.filter(c => c.role === 'Dueño').length;
                this.ownerError = owners > 1;
            },

            errorMessage(index) {
                let c = this.contacts[index];
                if (!c) return null;
                // Only show errors if they attempted to type or we're validating
                if (c.phone && c.phone.length !== 10) return "El teléfono requiere exactamente 10 dígitos.";
                return null;
            },

            async save() {
                this.checkRoles();
                if(this.ownerError) return;

                // Validate before sending
                for(let i=0; i<this.contacts.length; i++) {
                    let c = this.contacts[i];
                    if(!c.name) {
                        alert(`El nombre es obligatorio en el contacto #${i+1}`);
                        return;
                    }
                    if(!c.phone || c.phone.length !== 10) {
                        alert(`El teléfono debe tener 10 dígitos en el contacto: ${c.name || '#'+(i+1)}`);
                        return;
                    }
                }

                this.saving = true;
                try {
                    await this.$wire.saveContacts(this.clientId, this.contacts);
                } catch (e) {
                    console.error("Error guardando", e);
                    this.saving = false;
                }
            }
        }));
    </script>
    @endscript

</div>
