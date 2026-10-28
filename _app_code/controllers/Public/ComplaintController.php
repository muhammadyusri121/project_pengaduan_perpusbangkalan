<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Services\TrackingCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('public.complaint.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'subject'        => 'required|string|min:10|max:200',
            'description'    => 'required|string|min:20|max:2000',
            'location'       => 'nullable|string|max:200',
            'reporter_name'  => 'required|string|max:100',
            'reporter_phone' => 'required|string|regex:/^[0-9+\-\s]{8,20}$/',
            'reporter_email' => 'nullable|email|max:100',
            'is_anonymous'   => 'boolean',
            'attachments'    => 'nullable|array|max:5',
            'attachments.*'  => 'file|mimes:jpg,jpeg,png,gif,pdf|max:5120', // max 5MB per file
        ], [
            'category_id.required'    => 'Kategori harus dipilih.',
            'subject.required'        => 'Judul laporan wajib diisi.',
            'subject.min'             => 'Judul laporan minimal 10 karakter.',
            'description.required'    => 'Isi laporan wajib diisi.',
            'description.min'         => 'Isi laporan minimal 20 karakter.',
            'reporter_name.required'  => 'Nama pelapor wajib diisi.',
            'reporter_phone.required' => 'Nomor HP wajib diisi.',
            'reporter_phone.regex'    => 'Format nomor HP tidak valid.',
            'attachments.max'         => 'Maksimal 5 file lampiran.',
            'attachments.*.mimes'     => 'File harus berformat JPG, PNG, GIF, atau PDF.',
            'attachments.*.max'       => 'Ukuran file maksimal 5 MB.',
        ]);

        DB::beginTransaction();
        try {
            $trackingCode = TrackingCodeService::generate();

            $complaint = Complaint::create([
                'tracking_code'  => $trackingCode,
                'category_id'    => $validated['category_id'],
                'user_id'        => Auth::id(),
                'reporter_name'  => $validated['reporter_name'],
                'reporter_phone' => $validated['reporter_phone'],
                'reporter_email' => $validated['reporter_email'] ?? null,
                'subject'        => $validated['subject'],
                'description'    => $validated['description'],
                'location'       => $validated['location'] ?? null,
                'is_anonymous'   => $request->boolean('is_anonymous'),
                'status'         => 'pending',
                'priority'       => 'medium',
            ]);

            // Upload attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('complaints/' . $complaint->id, 'public');
                    ComplaintAttachment::create([
                        'complaint_id' => $complaint->id,
                        'file_path'    => $path,
                        'file_name'    => $file->getClientOriginalName(),
                        'file_type'    => $file->getClientOriginalExtension(),
                        'file_size'    => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('complaint.success', ['tracking_code' => $trackingCode])
                ->with('success', 'Laporan Anda berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.')->withInput();
        }
    }

    public function success(Request $request)
    {
        $trackingCode = $request->query('tracking_code');
        $complaint = Complaint::where('tracking_code', $trackingCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('public.complaint.success', compact('complaint'));
    }
}
