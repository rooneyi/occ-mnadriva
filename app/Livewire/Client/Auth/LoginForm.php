<?php
namespace App\Livewire\Client\Auth;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class LoginForm extends Component
{
    public $email = '';
    public $password = '';
    public $error = null;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function login()
    {
        $this->validate();
        $client = \App\Models\Client::where('email', $this->email)->first();
        if ($client && \Illuminate\Support\Facades\Hash::check($this->password, $client->password)) {
            \Illuminate\Support\Facades\Auth::login($client, true);
            $this->reset(['email', 'password']);
            $this->dispatch('login-success'); // Pour debug Livewire
            return $this->redirect(route('client.dashboard'), navigate: true);
        } else {
            $this->error = 'Identifiants invalides.';
        }
    }

    public function render()
    {
        return view('livewire.client.auth.login-form');
    }
}
