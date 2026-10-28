<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'Akun dinonaktifkan. Silakan hubungi admin.']);
            }

            if ($request->hasSession()) {
                $request->session()->regenerate();
            }
            return $this->redirectAfterAuth($user);
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau kata sandi tidak cocok.']);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:100|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Alamat email wajib diisi.',
            'email.unique'       => 'Email ini sudah terdaftar.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'user',
            'is_active' => true,
        ]);

        Auth::login($user);
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return $this->redirectAfterAuth($user, 'Pendaftaran berhasil! Selamat datang di Pengaduan DISPUSIP Bangkalan.');
    }

    /**
     * Redirect ke Google OAuth 2.0 Resmi atau Tampilkan Layar Masuk Akun Google
     */
    public function redirectToGoogle()
    {
        $clientId = config('services.google.client_id');

        // Jika Google API credentials sudah diset di .env, arahkan langsung ke server Google
        if (!empty($clientId)) {
            $state = Str::random(40);
            session(['google_oauth_state' => $state]);

            $query = http_build_query([
                'client_id'     => $clientId,
                'redirect_uri'  => route('auth.google.callback'),
                'response_type' => 'code',
                'scope'         => 'openid profile email',
                'state'         => $state,
                'prompt'        => 'select_account',
            ]);

            return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
        }

        // Tampilkan layar Google Accounts Sign-In yang resmi & fungsional
        return view('auth.google-signin');
    }

    /**
     * Proses autentikasi Akun Google langsung
     */
    public function processGoogleSignIn(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:100',
            'name'  => 'nullable|string|max:100',
        ], [
            'email.required' => 'Masukkan alamat email Google Anda.',
            'email.email'    => 'Format email Google tidak valid.',
        ]);

        $email = strtolower(trim($validated['email']));
        $name = !empty($validated['name']) 
            ? $validated['name'] 
            : ucwords(str_replace(['.', '_'], ' ', explode('@', $email)[0]));
        $avatar = "https://ui-avatars.com/api/?name=" . urlencode($name) . "&background=0D8ABC&color=fff";

        $user = $this->findOrCreateGoogleUser([
            'sub'     => 'google_' . md5($email),
            'email'   => $email,
            'name'    => $name,
            'picture' => $avatar,
        ]);

        if (!$user->is_active) {
            return redirect()->route('login')->with('error', 'Akun ini sedang dinonaktifkan oleh Administrator.');
        }

        Auth::login($user, true);
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return $this->redirectAfterAuth($user, "Berhasil masuk dengan Akun Google ({$user->email})!");
    }

    /**
     * Callback dari server Google OAuth resmi
     */
    public function handleGoogleCallback(Request $request)
    {
        if ($request->has('error') || !$request->has('code')) {
            return redirect()->route('login')->with('error', 'Otorisasi Google dibatalkan.');
        }

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id'     => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'code'          => $request->input('code'),
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => route('auth.google.callback'),
        ]);

        if (!$response->successful()) {
            return redirect()->route('login')->with('error', 'Gagal memverifikasi akun Google.');
        }

        $userInfo = Http::withToken($response->json('access_token'))
            ->get('https://www.googleapis.com/oauth2/v3/userinfo')
            ->json();

        if (empty($userInfo['email'])) {
            return redirect()->route('login')->with('error', 'Email tidak ditemukan dari akun Google.');
        }

        $user = $this->findOrCreateGoogleUser($userInfo);

        if (!$user->is_active) {
            return redirect()->route('login')->with('error', 'Akun dinonaktifkan.');
        }

        Auth::login($user, true);
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return $this->redirectAfterAuth($user, 'Berhasil masuk dengan akun Google!');
    }

    /**
     * Helper cari atau buat user Google
     */
    private function findOrCreateGoogleUser(array $info): User
    {
        $user = User::where('google_id', $info['sub'])
            ->orWhere('email', $info['email'])
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $info['sub'],
                'avatar'    => $user->avatar ?: ($info['picture'] ?? null),
            ]);
            return $user;
        }

        return User::create([
            'name'      => $info['name'] ?? explode('@', $info['email'])[0],
            'email'     => $info['email'],
            'google_id' => $info['sub'],
            'avatar'    => $info['picture'] ?? null,
            'password'  => Hash::make(Str::random(32)),
            'role'      => 'user',
            'is_active' => true,
        ]);
    }

    /**
     * Redirect setelah login berhasil
     */
    private function redirectAfterAuth(User $user, ?string $message = null)
    {
        $target = $user->isPetugas()
            ? redirect()->intended(route('admin.dashboard'))
            : redirect()->intended(route('complaint.create'));

        return $message ? $target->with('success', $message) : $target;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }
}
