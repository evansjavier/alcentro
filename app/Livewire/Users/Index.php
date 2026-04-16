<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Role;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 15;

    public $userId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->userId),
            ],
            'password' => $this->userId ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in([Role::ROLE_ADMIN, Role::ROLE_OWNER])],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function createUser()
    {
        $this->reset(['userId', 'name', 'email', 'password', 'role']);
        $this->role = Role::ROLE_ADMIN;
        $this->resetValidation();
        $this->dispatch('open-modal', 'user-modal');
    }

    public function editUser(User $user)
    {
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = ''; // Don't show password
        $this->role = $user->getRoleNames()->first() ?? Role::ROLE_ADMIN;

        $this->resetValidation();
        $this->dispatch('open-modal', 'user-modal');
    }

    public function save()
    {
        $this->validate();

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $user->name = $this->name;
            $user->email = $this->email;

            if (!empty($this->password)) {
                $user->password = Hash::make($this->password);
            }

            $user->save();
            $user->syncRoles([$this->role]);

            session()->flash('success', 'Usuario actualizado correctamente.');
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $user->assignRole($this->role);

            session()->flash('success', 'Usuario creado correctamente.');
        }

        $this->dispatch('close-modal', 'user-modal');
    }

    public function render()
    {
        if (!auth()->user()->hasAnyRole([Role::ROLE_OWNER, Role::ROLE_ADMIN])) {
            abort(403, 'No tienes permisos para ver esta sección.');
        }

        $users = User::query()
            ->when($this->search, function ($query) {
                $term = '%' . $this->search . '%';
                $query->where('name', 'like', $term)
                      ->orWhere('email', 'like', $term);
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.users.index', [
            'users' => $users,
            'roles' => [
                ['id' => Role::ROLE_ADMIN, 'name' => 'Administrador'],
                ['id' => Role::ROLE_OWNER, 'name' => 'Dueño'],
            ]
        ]);
    }
}
