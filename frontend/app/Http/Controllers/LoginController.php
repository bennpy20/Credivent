<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('login');
    }

    public function submit(Request $request)
    {
        $response = Http::post('http://localhost:3000/api/auth/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Simpan JWT & user info di session
            session([
                'jwt_token' => $data['token'],
                'user' => $data['user'],
            ]);

            // Redirect langsung
            return redirect()->route('member.index');
        } elseif ($response->status() === 403) {
            $message = $response->json()['message'] ?? 'Akun tidak aktif';
            return back()->with('acc_inactive', $message)->withInput();
        } else {
            return back()->withErrors(['login' => 'Email atau password salah'])->withInput();
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('member.index');
    }

    // Contoh: akses ke route API Node.js yang perlu token
    public function getProfile()
    {
        $token = session('jwt_token');

        $response = Http::withToken($token)->get('http://localhost:3000/api/auth/profile');

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
