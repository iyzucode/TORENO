<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PromoCode;
use Illuminate\Support\Carbon;

class PromoCodeManagement extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $promoCodeId;

    public $code;
    public $type = 'percentage';
    public $value;
    public $min_purchase = 0;
    public $max_discount;
    public $start_date;
    public $end_date;
    public $is_active = true;
    public $usage_limit;

    protected $rules = [
        'code' => 'required|string|unique:promo_codes,code',
        'type' => 'required|in:fixed,percentage',
        'value' => 'required|numeric|min:0',
        'min_purchase' => 'required|numeric|min:0',
        'max_discount' => 'nullable|numeric|min:0',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'is_active' => 'boolean',
        'usage_limit' => 'nullable|integer|min:1',
    ];

    public function render()
    {
        $promoCodes = PromoCode::latest()->paginate(10);
        return view('livewire.admin.promo-code-management', compact('promoCodes'))->layout('layouts.app');
    }

    public function create()
    {
        $this->resetFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        $promo = PromoCode::findOrFail($id);
        $this->promoCodeId = $promo->id;
        $this->code = $promo->code;
        $this->type = $promo->type;
        $this->value = $promo->value;
        $this->min_purchase = $promo->min_purchase;
        $this->max_discount = $promo->max_discount;
        $this->start_date = $promo->start_date ? $promo->start_date->format('Y-m-d\TH:i') : null;
        $this->end_date = $promo->end_date ? $promo->end_date->format('Y-m-d\TH:i') : null;
        $this->is_active = $promo->is_active;
        $this->usage_limit = $promo->usage_limit;

        $this->isModalOpen = true;
    }

    public function store()
    {
        $rules = $this->rules;
        if ($this->promoCodeId) {
            $rules['code'] = 'required|string|unique:promo_codes,code,' . $this->promoCodeId;
        }

        $this->validate($rules);

        PromoCode::updateOrCreate(['id' => $this->promoCodeId], [
            'code' => strtoupper($this->code),
            'type' => $this->type,
            'value' => $this->value,
            'min_purchase' => $this->min_purchase,
            'max_discount' => $this->max_discount ?: null,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'is_active' => $this->is_active,
            'usage_limit' => $this->usage_limit ?: null,
        ]);

        $this->closeModal();
        session()->flash('message', $this->promoCodeId ? 'Kupon diskon berhasil diperbarui.' : 'Kupon diskon berhasil ditambahkan.');
    }

    public function confirmDelete($id)
    {
        $this->promoCodeId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        PromoCode::findOrFail($this->promoCodeId)->delete();
        $this->closeModal();
        session()->flash('message', 'Kupon diskon berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $promo = PromoCode::findOrFail($id);
        $promo->update(['is_active' => !$promo->is_active]);
        session()->flash('message', 'Status kupon berhasil diubah.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->resetFields();
    }

    private function resetFields()
    {
        $this->promoCodeId = null;
        $this->code = '';
        $this->type = 'percentage';
        $this->value = '';
        $this->min_purchase = 0;
        $this->max_discount = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->is_active = true;
        $this->usage_limit = '';
    }
}
