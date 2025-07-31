<?php
namespace App\Livewire\Client\Auth;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class RegisterForm extends Component
{
    public $adresse;
    public $email;
    public $password;
    public $password_confirmation;

    public function register()
    {
        $this->validate([
            'adresse' => 'required|string',
            'email' => 'required|email|unique:clients,email',
            'password' => 'required|min:6|confirmed',
        ]);
        $client = Client::create([
            'adresse' => $this->adresse,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        Auth::login($client);
        return redirect()->route('client.dashboard');
    }

    public function render()
    {
        return view('livewire.client.auth.register-form');
    }
}

