<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    public $selectedNotification = null;
    public $selectedNotificationDetails = null;
    public $notifications;

    public function mount()
    {
        $this->notifications = Auth::user()->notifications->sortByDesc('created_at');
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications->where('id', $id)->first();
        if ($notification && $notification->read_at === null) {
            $notification->markAsRead();
            // Rafraîchir la liste pour l'affichage
            $this->notifications = Auth::user()->notifications->sortByDesc('created_at');
        }
    }

    public function showDetails($id)
    {
        $notification = Auth::user()->notifications->where('id', $id)->first();
        if ($notification && $notification->read_at === null) {
            $notification->markAsRead();
            $this->notifications = Auth::user()->notifications->sortByDesc('created_at');
        }
        $this->selectedNotification = $id;
        $this->selectedNotificationDetails = $notification;
    }

    public function render()
    {
        return view('livewire.client.notifications');
    }
}
