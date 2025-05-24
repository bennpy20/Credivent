<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('register');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'password' => 'required|string',
            'phone_number' => 'required|string|max:15'
        ]);

        $response = Http::post('http://localhost:3000/api/auth/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'phone_number' => $request->phone_number,
            'role' => 2,
            'acc_status' => 1
        ]);

        if ($response->successful()) {
            return redirect()->route('login')->with('success', 'Register berhasil! Silakan login.');
        } else {
            return back()->withErrors(['msg' => 'Register gagal: ' . $response->json('message')]);
        }
    }
}
