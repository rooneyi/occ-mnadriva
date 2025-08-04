<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UniversalLoginController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            switch ($user->role) {
                case 'controleur':
                    return redirect()->route('controleur.dashboard');
                case 'laborantin':
                    return redirect()->route('laborantin.dashboard');
                case 'chef_service':
                    return redirect()->route('chefservice.dashboard');
                case 'client':
                default:
                    return redirect()->route('client.dashboard');
            }
        }
        return view('auth.universal-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first(); // Suppression de la vérification du rôle
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, true);
            $request->session()->regenerate();
            // Redirection selon le rôle
            switch ($user->role) {
                case 'controleur':
                    return redirect()->route('controleur.dashboard');
                case 'laborantin':
                    return redirect()->route('laborantin.dashboard');
                case 'chef_service':
                    return redirect()->route('chefservice.dashboard');
                case 'client':
                default:
                    return redirect()->route('client.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Les informations de connexion sont incorrectes.',
        ]);
    }
}
