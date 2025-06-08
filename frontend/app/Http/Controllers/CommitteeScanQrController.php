<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CommitteeScanQrController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        return view('committee.scanqr.index');
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
        $qrData = $request->input('qr_data');

        try {
            $response = Http::post('http://localhost:3000/api/committee/committee-scanqr-store', [
                'qr_data' => $qrData
            ]);

            $data = $response->json(); // Ambil isi JSON apa pun statusnya

            return response()->json($data); // Return langsung, tidak peduli status HTTP-nya
        } catch (\Exception $e) {
            return response()->json(['valid' => false, 'message' => 'Terjadi kesalahan'], 500);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
