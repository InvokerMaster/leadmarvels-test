<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EditUser extends Component
{
    use AuthorizesRequests;
    
    /**
     * users
     *
     * @var array
     */
    public $users = [];

    public $search = '';

    public $pageSize = 10;

    public $page = 1;

    public $orderBy = 'id';

    public $orderDirection = 'asc';

    public $total = 0;

    public function toggleVerified(User $user) {
        $this->authorize('user.edit', $user);
        $user->email_verified_at = null;
        $user->save();
    }

    public function updateState() {
        $this->total = $this->users = User::general()
            ->filter($this->search)
            ->orderBy($this->orderBy)
            ->count();

        $this->users = User::general()
            ->filter($this->search)
            ->orderBy($this->orderBy, $this->orderDirection)
            ->skip($this->pageSize * ($this->page - 1))
            ->limit($this->pageSize)
            ->get();
    }

    public function render()
    {
        $this->updateState();
        return view('livewire.edit-user');
    }
}
