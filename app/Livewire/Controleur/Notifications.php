<?php

namespace App\Livewire\Controleur;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    public $notifications;

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->notifications = $user->notifications->sortByDesc('created_at');
        } else {
            $this->notifications = collect();
        }
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        if (!$user) return;

        $notification = $user->notifications->where('id', $id)->first();
        if ($notification && $notification->read_at === null) {
            $notification->markAsRead();
            $this->notifications = $user->notifications->sortByDesc('created_at');
        }
    }

    public function render()
    {
        return view('livewire.controleur.notifications');
    }
}