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

    public $orderBy = 'name';

    public $orderDirection = 'asc';

    public $total = 0;

    public $totalVerified = 0;

    public $verifiedPercentage = 0;

    public function toggleVerified(User $user) {
        $this->authorize('user.edit', $user);
        if ($user->email_verified_at) {
            $user->email_verified_at = null;
        } else {
            $user->email_verified_at = now();
        }
        $user->save();
    }

    public function nextPage() {
        $this->page ++;
    }

    public function prevPage() {
        $this->page --;
    }

    public function sort($key) {
        if ($this->orderBy != $key) {
            $this->orderBy = $key;
            $this->orderDirection = 'asc';
        } else {
            if ($this->orderDirection == 'asc') {
                $this->orderDirection = 'desc';
            } else {
                $this->orderDirection = 'asc';
            }
        }
    }

    public function updateState() {
        $this->page = min(ceil($this->total / $this->pageSize), $this->page);
        $this->page = max(1, $this->page);

        $this->total = User::general()
            ->filter($this->search)
            ->orderBy($this->orderBy)
            ->count();

        $this->totalVerified = User::general()
            ->filter($this->search)
            ->whereNotNull('email_verified_at')
            ->count();

        $this->verifiedPercentage = 0;

        if ($this->total > 0) {
            $this->verifiedPercentage = round(($this->totalVerified / $this->total) * 100, 2);
        }

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
