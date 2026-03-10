<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;

class Create extends Component
{
    public $name;
    public $tax_id;
    public $email;
    public $phone;

    public function save()
    {
        $validated = $this->validate([
            "name" => "required|string|max:255",
            "tax_id" => "nullable|string|max:255|unique:clients,tax_id",
            "email" => "nullable|email|max:255",
            "phone" => "nullable|string|max:255",
        ]);

        Client::create($validated);

        session()->flash("status", "Empresa registrada exitosamente.");

        return redirect()->route("clients.index");
    }

    public function render()
    {
        return view("livewire.clients.create")->layout("layouts.app");
    }
}

