<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'petugas']);
    }

    public function index()
    {
        $categories = Category::withCount('complaints')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.form', ['category' => new Category()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);
        Category::create($validated);
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($category)
    {
        $category = $category instanceof Category && $category->exists ? $category : Category::findOrFail($category);
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, $category)
    {
        $category = $category instanceof Category && $category->exists ? $category : Category::findOrFail($category);
        $validated = $this->validateCategory($request);
        $category->update($validated);
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($category)
    {
        $category = $category instanceof Category && $category->exists ? $category : Category::findOrFail($category);
        if ($category->complaints()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki laporan terkait.');
        }
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    private function validateCategory(Request $request): array
    {
        return $request->validate([
            'name'        => 'required|string|max:100',
            'icon'        => 'required|string|max:50',
            'color'       => 'required|string|max:20',
            'description' => 'nullable|string|max:300',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer|min:0',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'icon.required' => 'Ikon kategori wajib dipilih.',
        ]);
    }
}
