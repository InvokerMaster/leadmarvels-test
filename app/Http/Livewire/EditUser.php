<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EditUser extends Component
{
    use AuthorizesRequests;
    
    public $isVerified = false;

    public function toggleVerified(User $user) {
        $this->authorize('user.edit', $user);
    }

    public function render()
    {
        return view('livewire.edit-user');
    }
}
