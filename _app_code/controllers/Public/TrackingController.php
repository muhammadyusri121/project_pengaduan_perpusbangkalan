<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Tampilkan semua laporan milik user yang sedang login
        $complaints = Complaint::with('category')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('public.tracking.index', compact('complaints'));
    }

    public function show(Request $request)
    {
        $trackingCode = strtoupper(trim($request->input('tracking_code', '')));

        if (empty($trackingCode)) {
            return back()->with('error', 'Masukkan kode tracking laporan Anda.');
        }

        $complaint = Complaint::with(['category', 'publicResponses.user', 'attachments'])
            ->where('tracking_code', $trackingCode)
            ->where('user_id', Auth::id()) // hanya bisa lihat laporan sendiri
            ->first();

        if (!$complaint) {
            return back()->with('error', 'Laporan tidak ditemukan. Pastikan kode tracking benar.');
        }

        return view('public.tracking.show', compact('complaint'));
    }
}
