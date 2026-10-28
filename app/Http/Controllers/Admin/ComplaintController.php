<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'petugas']);
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'category', 'priority', 'search', 'date_from', 'date_to']);

        $complaints = Complaint::with('category')
            ->filter($filters)
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::active()->get();

        $counts = [
            'all'         => Complaint::count(),
            'pending'     => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved'    => Complaint::where('status', 'resolved')->count(),
            'rejected'    => Complaint::where('status', 'rejected')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'categories', 'filters', 'counts'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['category', 'user', 'attachments', 'responses.user']);
        return view('admin.complaints.show', compact('complaint'));
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status'           => 'required|in:pending,in_progress,resolved,rejected',
            'message'          => 'required|string|min:10|max:1000',
            'priority'         => 'nullable|in:low,medium,high',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500',
            'is_public'        => 'boolean',
        ], [
            'status.required'            => 'Status harus dipilih.',
            'message.required'           => 'Pesan tanggapan wajib diisi.',
            'message.min'                => 'Pesan tanggapan minimal 10 karakter.',
            'rejection_reason.required_if' => 'Alasan penolakan wajib diisi jika laporan ditolak.',
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $complaint->status;

            // Update complaint status
            $updateData = ['status' => $validated['status']];
            if ($validated['priority'] ?? null) {
                $updateData['priority'] = $validated['priority'];
            }
            if ($validated['status'] === 'resolved') {
                $updateData['resolved_at'] = now();
            }
            if ($validated['status'] === 'rejected') {
                $updateData['rejection_reason'] = $validated['rejection_reason'];
            }

            $complaint->update($updateData);

            // Buat response / catatan
            ComplaintResponse::create([
                'complaint_id'  => $complaint->id,
                'user_id'       => Auth::id(),
                'message'       => $validated['message'],
                'status_change' => $validated['status'] !== $oldStatus ? $validated['status'] : null,
                'is_public'     => $request->boolean('is_public', true),
            ]);

            DB::commit();

            return back()->with('success', 'Status laporan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status. Silakan coba lagi.');
        }
    }

    public function addResponse(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'message'   => 'required|string|min:5|max:1000',
            'is_public' => 'boolean',
        ]);

        ComplaintResponse::create([
            'complaint_id' => $complaint->id,
            'user_id'      => Auth::id(),
            'message'      => $validated['message'],
            'is_public'    => $request->boolean('is_public', true),
        ]);

        return back()->with('success', 'Catatan berhasil ditambahkan.');
    }

    public function destroy(Complaint $complaint)
    {
        // Hanya admin yang bisa hapus
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat menghapus laporan.');
        }

        // Hapus file lampiran
        foreach ($complaint->attachments as $attachment) {
            \Storage::disk('public')->delete($attachment->file_path);
        }

        $complaint->delete();

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
