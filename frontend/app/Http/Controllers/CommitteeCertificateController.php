<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CommitteeCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $response = Http::get('http://localhost:3000/api/committee/committee-certificate-index');

        if ($response->successful()) {
            $attendances = $response->json();

            $attendances = array_map(function ($item) {
                $start = \Carbon\Carbon::parse($item['session']['session_start'])->locale('id');
                $end = \Carbon\Carbon::parse($item['session']['session_end'])->locale('id');

                // Format jam-nya (pukul)
                $timeRange = $start->format('H:i') . ' - ' . $end->format('H:i');

                // Format tanggal-nya
                if ($start->isSameDay($end)) {
                    $dateRange = $start->translatedFormat('d F Y'); // ex: 27 Mei 2025
                } else {
                    if ($start->format('Y') == $end->format('Y')) {
                        if ($start->format('F') == $end->format('F')) {
                            $dateRange = $start->format('d') . '-' . $end->translatedFormat('d F Y'); // 27-28 Mei 2025
                        } else {
                            $dateRange = $start->translatedFormat('d F') . ' - ' . $end->translatedFormat('d F Y'); // 27 Mei - 02 Juni 2025
                        }
                    } else {
                        $dateRange = $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y'); // 27 Mei 2025 - 02 Juni 2026
                    }
                }

                $item['session']['session_range'] = $dateRange . ' ' . $timeRange; // Gabung tanggal + waktu
                return $item;
            }, $attendances);

            return view('committee.certificate.index', compact('attendances'));
        } else {
            return back()->withErrors(['error' => 'Gagal mengambil data peserta untuk sertifikat']);
        }
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
