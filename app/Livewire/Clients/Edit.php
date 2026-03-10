<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;

class Edit extends Component
{
    public Client $client;
    public $name;
    public $tax_id;
    public $email;
    public $phone;

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->name = $client->name;
        $this->tax_id = $client->tax_id;
        $this->email = $client->email;
        $this->phone = $client->phone;
    }

    public function save()
    {
        $validated = $this->validate([
            "name" => "required|string|max:255",
            "tax_id" => "nullable|string|max:255|unique:clients,tax_id," . $this->client->id,
            "email" => "nullable|email|max:255",
            "phone" => "nullable|string|max:255",
        ]);

        $this->client->update($validated);

        session()->flash("status", "Empresa actualizada exitosamente.");

        return redirect()->route("clients.index");
    }

    public function render()
    {
        return view("livewire.clients.create")->layout("layouts.app");
    }
}

