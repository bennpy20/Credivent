<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'certificate_link' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $certificateUrl = null;

        if ($request->hasFile('certificate_link')) {
            $file = $request->file('certificate_link');
            $filename = 'certificate_' . $id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('certificates'), $filename);
            $certificateUrl = url('certificates/' . $filename);
        }

        $response = Http::put("http://localhost:3000/api/committee/committee-certificate-update/{$id}", [
            'certificate_link' => $certificateUrl
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Sertifikat berhasil diunggah');
        } else {
            return back()->with('error', 'Gagal mengunggah sertifikat');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
