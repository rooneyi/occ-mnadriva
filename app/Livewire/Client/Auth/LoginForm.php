<?php
namespace App\Livewire\Client\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class LoginForm extends Component
{
    public $email = '';
    public $password = '';
    public $error ;
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
        $user = User::where('email', $this->email)->where('role', 'client')->first();
        if ($user && Hash::check($this->password, $user->password)) {
            Auth::login($user, true); // Connexion avec le guard Laravel par défaut
            session()->regenerate(); // Sécurité session
            $this->redirect(route('client.dashboard'));
        } else {
            $this->addError('email', 'Identifiants invalides.');
        }
    }

    public function render()
    {
        return view('livewire.client.auth.login-form');
    }
}
