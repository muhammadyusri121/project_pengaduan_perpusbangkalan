<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Complaint;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total'       => Complaint::count(),
            'pending'     => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved'    => Complaint::where('status', 'resolved')->count(),
        ];

        $categories = Category::active()->withCount('complaints')->get();

        $recentResolved = Complaint::with('category')
            ->where('status', 'resolved')
            ->latest('resolved_at')
            ->limit(5)
            ->get();

        return view('public.home', compact('stats', 'categories', 'recentResolved'));
    }
}
