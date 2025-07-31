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
        $client = Client::where('email', $this->email)->first();
        if ($client && Hash::check($this->password, $client->password)) {
            Auth::guard('client')->login($client, false); // Utilise le guard client
            return redirect()->route('client.dashboard');
        } else {
            $this->addError('email', 'Identifiants invalides.');
        }
    }

    public function render()
    {
        return view('livewire.client.auth.login-form');
    }
}
