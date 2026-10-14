<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('pharmacy')
            ->when($request->role, fn ($q, $r) => $q->where('role', $r))
            ->when($request->q, fn ($q, $s) => $q->where(fn ($x) => $x->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%")))
            ->latest()->paginate(12)->withQueryString();
        return view('admin.users', compact('users'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id() || $user->isAdmin()) {
            return back()->with('error', 'Administrator accounts cannot be deleted here.');
        }
        $user->delete();
        return back()->with('success', 'User deleted.');
    }
}
