<?php

namespace App\Livewire\Admin;

use App\Models\Menu;
use App\Models\MenuCategory;
use Livewire\Component;
use Livewire\WithFileUploads;

class MenuManagement extends Component
{
    use WithFileUploads;

    public $name, $description, $price, $category_id, $image;
    public $is_available = true;
    public $menu_id;
    public $isModalOpen = false;

    public function render()
    {
        $menusByCategory = Menu::with('category')
            ->orderBy('sort_order')
            ->get()
            ->sortBy(fn($menu) => $menu->category->sort_order ?? 999)
            ->groupBy(fn($menu) => $menu->category->name ?? 'Tanpa Kategori');

        return view('livewire.admin.menu-management', [
            'menusByCategory' => $menusByCategory,
            'categories' => MenuCategory::orderBy('sort_order')->get(),
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->category_id = '';
        $this->image = null;
        $this->is_available = true;
        $this->menu_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:menu_categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = cloudinary()->uploadApi()->upload($this->image->getRealPath(), [
                'folder' => 'TORENO/MENU'
            ])['secure_url'];
        }

        if ($this->menu_id) {
            $menu = Menu::find($this->menu_id);
            if ($imagePath) {
                // Delete old image if needed (Cloudinary doesn't need manual deletion for now)
            }
            $menu->update([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'category_id' => $this->category_id,
                'is_available' => $this->is_available,
                'image_url' => $imagePath ?: $menu->image_url,
            ]);
            session()->flash('message', 'Menu berhasil diperbarui.');
        } else {
            $maxOrder = Menu::where('category_id', $this->category_id)->max('sort_order') ?? -1;
            Menu::create([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'category_id' => $this->category_id,
                'is_available' => $this->is_available,
                'image_url' => $imagePath,
                'sort_order' => $maxOrder + 1,
            ]);
            session()->flash('message', 'Menu berhasil ditambahkan.');
        }

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $this->menu_id = $id;
        $this->name = $menu->name;
        $this->description = $menu->description;
        $this->price = $menu->price;
        $this->category_id = $menu->category_id;
        $this->is_available = $menu->is_available;
    
        $this->openModal();
    }

    public function delete($id)
    {
        Menu::find($id)->delete();
        session()->flash('message', 'Menu Dihapus.');
    }

    public function toggleAvailability($id)
    {
        $menu = Menu::find($id);
        $menu->is_available = !$menu->is_available;
        $menu->save();
    }

    public function updateMenuOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            Menu::where('id', $id)->update(['sort_order' => $index]);
        }
    }
}
