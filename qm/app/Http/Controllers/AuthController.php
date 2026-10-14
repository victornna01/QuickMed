<?php
namespace App\Http\Controllers;

use App\Models\{Pharmacy, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class AuthController extends Controller
{
    public function showRegister() { return view('auth.register'); }
    public function showLogin() { return view('auth.login'); }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150|unique:users,email',
            'phone' => 'required|string|max:30',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:customer,pharmacy_owner',
            'pharmacy_name' => 'required_if:role,pharmacy_owner|nullable|string|max:150',
            'pharmacy_address' => 'required_if:role,pharmacy_owner|nullable|string|max:255',
            'pharmacy_area' => 'required_if:role,pharmacy_owner|nullable|string|max:100',
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create(['name' => $data['name'], 'email' => $data['email'], 'phone' => $data['phone'], 'password' => $data['password'], 'role' => $data['role']]);
            if ($data['role'] === 'pharmacy_owner') {
                Pharmacy::create(['user_id' => $user->id, 'name' => $data['pharmacy_name'], 'address' => $data['pharmacy_address'],
                    'area' => $data['pharmacy_area'], 'phone' => $data['phone'], 'email' => $data['email'], 'status' => 'pending']);
            }
            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route($user->isOwner() ? 'pharmacy.dashboard' : 'home')
            ->with('success', $user->isOwner() ? 'Account created. Your pharmacy will appear publicly once an administrator verifies it.' : 'Welcome to QuickMed!');
    }

    public function login(Request $request)
    {
        $cred = $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (! Auth::attempt($cred, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        $user = Auth::user();
        $home = $user->isAdmin() ? route('admin.dashboard') : ($user->isOwner() ? route('pharmacy.dashboard') : route('home'));
        return redirect()->intended($home);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
