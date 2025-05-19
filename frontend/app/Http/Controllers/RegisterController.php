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
            'phone_number' => 'required|string',
            'major' => 'required|string|max:50'
        ]);

        $response = Http::post('http://localhost:3000/api/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 2,
            'phone_number' => $request->phone_number,
            'major' => $request->major
        ]);

        if ($response->successful()) {
            return redirect()->route('login.form')->with('success', 'Register berhasil! Silakan login.');
        } else {
            return back()->withErrors(['msg' => 'Register gagal: ' . $response->json('message')]);
        }
    }
}
