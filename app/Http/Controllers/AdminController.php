<?php
// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chantier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_chantiers' => Chantier::count(),
            'chantiers_actifs' => Chantier::where('statut', 'en_cours')->count(),
            'chantiers_termines' => Chantier::where('statut', 'termine')->count(),
        ];

        return view('admin.index', compact('stats'));
    }

    public function users()
    {
        $users = User::orderBy('name')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,commercial,client'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
        ]);

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,commercial,client'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroyUser(User $user)
    {
        // Empêcher la suppression du dernier admin
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return redirect()->back()->with('error', 'Impossible de supprimer le dernier administrateur.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function toggleUser(User $user)
    {
        $user->update(['active' => !$user->active]);
        
        $status = $user->active ? 'activé' : 'désactivé';
        return redirect()->back()->with('success', "Utilisateur {$status} avec succès.");
    }

    public function statistics()
    {
        $stats = [
            'users_by_role' => User::selectRaw('role, COUNT(*) as count')
                                 ->groupBy('role')
                                 ->pluck('count', 'role'),
            'chantiers_by_status' => Chantier::selectRaw('statut, COUNT(*) as count')
                                           ->groupBy('statut')
                                           ->pluck('count', 'statut'),
            'chantiers_by_month' => Chantier::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                                          ->whereYear('created_at', date('Y'))
                                          ->groupBy('month')
                                          ->pluck('count', 'month'),
            'average_progress' => Chantier::avg('avancement_global'),
        ];

        return view('admin.statistics', compact('stats'));
    }
}