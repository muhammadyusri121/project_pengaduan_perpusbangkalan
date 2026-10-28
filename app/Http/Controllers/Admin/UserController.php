<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = User::withCount(['complaints', 'responses']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all'        => User::count(),
            'petugas'    => User::where('role', 'petugas')->count(),
            'admin'      => User::where('role', 'admin')->count(),
            'masyarakat' => User::where('role', 'user')->count(),
        ];

        return view('admin.users.index', compact('users', 'counts'));
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()],
            'role'     => 'required|in:admin,petugas,user',
        ], [
            'name.required'     => 'Nama wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'role.required'     => 'Role wajib dipilih.',
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => $validated['role'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($user)
    {
        $user = $user instanceof User && $user->exists ? $user : User::findOrFail($user);
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, $user)
    {
        $user = $user instanceof User && $user->exists ? $user : User::findOrFail($user);

        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => ['nullable', Password::min(8)->letters()->mixedCase()->numbers()],
            'role'      => 'required|in:admin,petugas,user',
            'is_active' => 'boolean',
        ]);

        $updateData = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'role'      => $validated['role'],
            'is_active' => $request->boolean('is_active'),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($user)
    {
        $user = $user instanceof User && $user->exists ? $user : User::findOrFail($user);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;

        // Lepaskan referensi user_id pada laporan & tanggapan agar riwayat aduan tetap aman
        $user->complaints()->update(['user_id' => null]);
        $user->responses()->update(['user_id' => null]);

        $user->delete();

        return back()->with('success', "Akun '{$userName}' telah berhasil dihapus secara permanen.");
    }

    public function toggleActive($user)
    {
        $user = $user instanceof User && $user->exists ? $user : User::findOrFail($user);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun berhasil {$status}.");
    }
}
