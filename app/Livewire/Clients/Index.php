<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'all';
    public string $sort = 'latest';
    public int $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'sort' => ['except' => 'latest'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingSort(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $clients = Client::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $term = '%' . $this->search . '%';

                    $q->where('name', 'like', $term)
                        ->orWhere('tax_id', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone', 'like', $term);
                });
            })
            ->when($this->status === 'with_email', function ($query) {
                $query->whereNotNull('email')->where('email', '!=', '');
            })
            ->when($this->status === 'without_email', function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('email')->orWhere('email', '=', '');
                });
            })
            ->when($this->sort === 'name', function ($query) {
                $query->orderBy('name');
            })
            ->when($this->sort === 'oldest', function ($query) {
                $query->orderBy('created_at');
            })
            ->when($this->sort === 'latest', function ($query) {
                $query->orderByDesc('created_at');
            })
            ->with(['contacts' => function ($query) {
                $query->whereIn('role', ['Dueño', 'Encargado']);
            }])
            ->paginate($this->perPage);

        return view('livewire.clients.index', [
            'clients' => $clients,
        ]);
    }

    public function mount()
    {
    }

    public function getContacts(Client $client)
    {
        return $client->contacts()->get()->toArray();
    }

    public function saveContacts(Client $client, array $contacts)
    {
        $ownersCount = 0;

        foreach ($contacts as $contact) {
            if ($contact['role'] === 'Dueño') {
                $ownersCount++;
            }

            // Validar teléfono (ej: México 10 dígitos)
            if (empty($contact['phone']) || !preg_match('/^[0-9]{10}$/', $contact['phone'])) {
                $this->addError("contacts.{$contact['id']}", "El teléfono del contacto {$contact['name']} debe tener exactamente 10 dígitos numéricos.");
                return;
            }

            if (empty($contact['name'])) {
                $this->addError("contacts.{$contact['id']}", 'El nombre del contacto es obligatorio.');
                return;
            }
        }

        if ($ownersCount > 1) {
            $this->addError('contacts', 'Solo puede haber un (1) contacto con el rol de Dueño.');
            return;
        }

        // Si pasa validaciones, guardamos
        $client->contacts()->delete(); // Borramos los anteriores (todos) porque en el form se manejarán todos
        foreach ($contacts as $contact) {
            $client->contacts()->create([
                'name' => $contact['name'],
                'phone' => $contact['phone'],
                'email' => $contact['email'] ?? null,
                'notes' => $contact['notes'] ?? null,
                'role' => $contact['role'],
            ]);
        }

        $this->resetErrorBag();
        $this->dispatch('contacts-saved');
    }
}
