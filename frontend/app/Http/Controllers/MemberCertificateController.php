<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MemberCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = session('user'); // asumsi sesi user disimpan
        $userId = $user['id'];

        $response = Http::get("http://localhost:3000/api/member/member-certificate-index", [
            'user_id' => $userId
        ]);

        if ($response->successful()) {
            $certificates = $response->json();

            $certificates = array_map(function ($item) {
                $start = \Carbon\Carbon::parse($item['session']['session_start'])->locale('id');
                $end = \Carbon\Carbon::parse($item['session']['session_end'])->locale('id');

                // Format waktu seperti fitur panitia
                if ($start->isSameDay($end)) {
                    $item['date_display'] = $start->translatedFormat('d F Y') . ' ' . $start->format('H:i') . ' - ' . $end->format('H:i');
                } elseif ($start->format('Y') == $end->format('Y')) {
                    if ($start->format('m') == $end->format('m')) {
                        $item['date_display'] = $start->format('d') . '-' . $end->translatedFormat('d F Y');
                    } else {
                        $item['date_display'] = $start->translatedFormat('d F') . ' - ' . $end->translatedFormat('d F Y');
                    }
                } else {
                    $item['date_display'] = $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y');
                }

                return $item;
            }, $certificates);

            return view('member.certificate.index', compact('certificates'));
        }

        return back()->with('error', 'Gagal memuat daftar sertifikat');
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
