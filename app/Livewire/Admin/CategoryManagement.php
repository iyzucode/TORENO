<?php

namespace App\Livewire\Admin;

use App\Models\MenuCategory;
use Livewire\Component;

class CategoryManagement extends Component
{
    public $name;
    public $category_id;
    public $isModalOpen = false;

    public function render()
    {
        return view('livewire.admin.category-management', [
            'categories' => MenuCategory::orderBy('sort_order')->get()
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
        $this->category_id = '';
    }

    public function store()
    {
        $rules = ['name' => 'required|string|max:100'];
        
        // Unique check: exclude current category when editing
        if ($this->category_id) {
            $rules['name'] .= '|unique:menu_categories,name,' . $this->category_id;
        } else {
            $rules['name'] .= '|unique:menu_categories,name';
        }

        $this->validate($rules);

        if ($this->category_id) {
            $category = MenuCategory::findOrFail($this->category_id);
            $category->update(['name' => $this->name]);
        } else {
            $maxOrder = MenuCategory::max('sort_order') ?? -1;
            MenuCategory::create([
                'name' => $this->name,
                'sort_order' => $maxOrder + 1,
            ]);
        }

        session()->flash('message', $this->category_id ? 'Kategori diperbarui.' : 'Kategori ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $category = MenuCategory::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;
        $this->openModal();
    }

    public function delete($id)
    {
        $category = MenuCategory::findOrFail($id);
        
        if ($category->menus()->count() > 0) {
            session()->flash('error', 'Kategori tidak dapat dihapus karena masih memiliki ' . $category->menus()->count() . ' menu.');
            return;
        }

        $category->delete();
        session()->flash('message', 'Kategori dihapus.');
    }

    public function updateOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            MenuCategory::where('id', $id)->update(['sort_order' => $index]);
        }
    }
}
