<?php

namespace App\Livewire\Shared;

use App\Livewire\Actions\Logout;
use Livewire\Component;

class TopbarUserDropdown extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.shared.topbar-user-dropdown');
    }
}
