<?php

namespace App\Livewire\Admin;

use App\Models\Promotion;
use Livewire\Component;
use Livewire\WithFileUploads;

class PromotionManagement extends Component
{
    use WithFileUploads;

    public $title, $image, $is_active = true, $promotion_id;
    public $isModalOpen = false;

    public function render()
    {
        return view('livewire.admin.promotion-management', [
            'promotions' => Promotion::orderBy('sort_order')->get()
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
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->image = null;
        $this->is_active = true;
        $this->promotion_id = null;
    }

    public function store()
    {
        $this->validate([
            'title' => 'nullable|string|max:255',
            'image' => $this->promotion_id ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = cloudinary()->uploadApi()->upload($this->image->getRealPath(), [
                'folder' => 'TORENO/PROMOTION'
            ])['secure_url'];
        }

        if ($this->promotion_id) {
            $promo = Promotion::find($this->promotion_id);
            $promo->update([
                'title' => $this->title,
                'is_active' => $this->is_active,
                'image_url' => $imagePath ?: $promo->image_url,
            ]);
            session()->flash('message', 'Promosi berhasil diperbarui.');
        } else {
            $maxOrder = Promotion::max('sort_order') ?? -1;
            Promotion::create([
                'title' => $this->title,
                'is_active' => $this->is_active,
                'image_url' => $imagePath,
                'sort_order' => $maxOrder + 1,
            ]);
            session()->flash('message', 'Promosi berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $promo = Promotion::findOrFail($id);
        $this->promotion_id = $id;
        $this->title = $promo->title;
        $this->is_active = $promo->is_active;
    
        $this->openModal();
    }

    public function delete($id)
    {
        Promotion::find($id)->delete();
        session()->flash('message', 'Promosi berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $promo = Promotion::find($id);
        $promo->is_active = !$promo->is_active;
        $promo->save();
    }

    public function updateOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            Promotion::where('id', $id)->update(['sort_order' => $index]);
        }
    }

    // --- Popup Promotion Management ---
    public $popup_image_url;
    public $popup_image_upload;
    public $popup_is_active = false;

    public function mount()
    {
        $popup = \App\Models\PopupPromotion::first();
        if ($popup) {
            $this->popup_image_url = $popup->image_url;
            $this->popup_is_active = $popup->is_active;
        } else {
            $this->popup_image_url = null;
            $this->popup_is_active = false;
        }
    }

    public function savePopup()
    {
        $popup = \App\Models\PopupPromotion::first() ?? new \App\Models\PopupPromotion();

        if ($this->popup_image_upload) {
            $this->validate([
                'popup_image_upload' => 'image|max:2048',
            ]);

            $imagePath = cloudinary()->uploadApi()->upload($this->popup_image_upload->getRealPath(), [
                'folder' => 'TORENO/PROMOTION/POPUP'
            ])['secure_url'];

            $popup->image_url = $imagePath;
            $this->popup_image_url = $imagePath;
            $this->popup_image_upload = null;
        }

        $popup->is_active = $this->popup_is_active;
        $popup->save();

        session()->flash('popup_message', 'Pengaturan Pop-up Promosi berhasil disimpan.');
    }

    public function deletePopup()
    {
        $popup = \App\Models\PopupPromotion::first();
        if ($popup) {
            $popup->image_url = null;
            $popup->is_active = false;
            $popup->save();
        }
        
        $this->popup_image_url = null;
        $this->popup_image_upload = null;
        $this->popup_is_active = false;

        session()->flash('popup_message', 'Gambar Pop-up Promosi berhasil dihapus.');
    }
}
