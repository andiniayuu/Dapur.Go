<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    // Menampilkan semua menu
    public function index(Request $request)
    {
        $query = Menu::with('category')->latest();

        // Pencarian berdasarkan nama menu
        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $menus = $query->paginate(10)->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.menus.index', compact(
            'menus',
            'categories'
        ));
    }

    // Menampilkan form tambah menu
    public function create()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.menus.create', compact('categories'));
    }

    // Menyimpan menu baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        unset($validated['image']);

        $validated['slug'] = $this->makeUniqueSlug(
            $validated['name']
        );

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('menus', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available');

        Menu::create($validated);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit(Menu $menu)
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.menus.edit', compact(
            'menu',
            'categories'
        ));
    }

    // Memperbarui menu
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $validated['slug'] = $this->makeUniqueSlug(
            $validated['name'],
            $menu->id
        );

        // Mengganti gambar lama jika ada gambar baru
        if ($request->hasFile('image')) {

            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }

            $validated['image'] = $request->file('image')
                ->store('menus', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available');

        $menu->update($validated);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    // Menghapus menu
    public function destroy(Menu $menu)
    {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }

    // Membuat slug unik
    private function makeUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {

        $base = Str::slug($name) ?: 'menu';
        $slug = $base;
        $counter = 1;

        while (
            Menu::where('slug', $slug)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->exists()
        ) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
