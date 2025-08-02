<?php
namespace App\Livewire\Controleur\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class LoginForm extends Component
{
    public $email = '';
    public $password = '';
    public $error;
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
        Log::info('Tentative login CONTROLEUR', ['email' => $this->email, 'password' => $this->password]);
        $user = User::where('email', $this->email)->where('role', 'controleur')->first();
        Log::info('User trouvé CONTROLEUR', ['user' => $user]);
        if ($user && Hash::check($this->password, $user->password)) {
            Auth::login($user, true);
            session()->regenerate();
            $this->redirect(route('controleur.dashboard'));
        } else {
            $this->addError('email', 'Identifiants invalides.');
        }
    }

    public function render()
    {
        return view('livewire.controleur.auth.login-form');
    }
}
