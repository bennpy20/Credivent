<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminCommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::get('http://localhost:3000/api/admin/admin-committee-index');

        if ($response->successful()) {
            $users = $response->json();
            return view('admin.committee.index', compact('users'));
        } else {
            return back()->withErrors(['error' => 'Gagal mengambil data']);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.committee.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'password' => 'required|string'
        ]);

        $response = Http::post('http://localhost:3000/api/admin/admin-committee-store', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 3,
            'acc_status' => 1
        ]);

        if ($response->successful()) {
            return redirect()->route('admin.committee.index')->with('success', 'Data panitia berhasil ditambahkan');
        } else {
            return back()->with(['error' => 'Gagal menambahkan data panitia']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $response = Http::get("http://localhost:3000/api/admin/admin-committee-edit/{$id}");

        if ($response->successful()) {
            $user = $response->json();
            return view('admin.committee.edit', compact('user'));
        } else {
            return back()->withErrors(['error' => 'Gagal mengambil data user']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'acc_status' => 'required|integer|in:1,2'
        ]);

        $response = Http::put("http://localhost:3000/api/admin/admin-committee-update/{$id}", [
            'name' => $request->name,
            'email' => $request->email,
            'acc_status' => (int) $request->acc_status,
        ]);

        if ($response->successful()) {
            return redirect()->route('admin.committee.index')->with('success', 'Data panitia berhasil diperbarui');
        } else {
            return back()->with(['error' => 'Gagal memperbarui data panitia']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = Http::delete("http://localhost:3000/api/admin/admin-committee-destroy/{$id}");

        if ($response->successful()) {
            return redirect()->route('admin.committee.index')->with('success', 'Data panitia berhasil dihapus');
        } else {
            return back()->with(['error' => 'Gagal menghapus data panitia']);
        }
    }
}
