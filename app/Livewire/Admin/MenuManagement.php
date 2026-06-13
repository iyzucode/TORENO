<?php

namespace App\Livewire\Admin;

use App\Models\Menu;
use Livewire\Component;
use Livewire\WithFileUploads;

class MenuManagement extends Component
{
    use WithFileUploads;

    public $name, $description, $price, $category, $image;
    public $is_available = true;
    public $menu_id;
    public $isModalOpen = false;

    public function render()
    {
        return view('livewire.admin.menu-management', [
            'menus' => Menu::all()
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
        $this->category = '';
        $this->image = null;
        $this->is_available = true;
        $this->menu_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('menus', 'public');
        }

        Menu::updateOrCreate(['id' => $this->menu_id ?: null], [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->category,
            'is_available' => $this->is_available,
            'image_url' => $imagePath ?: ($this->menu_id ? Menu::find($this->menu_id)->image_url : null),
        ]);

        session()->flash('message', $this->menu_id ? 'Menu Diperbarui.' : 'Menu Ditambahkan.');

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
        $this->category = $menu->category;
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
}
