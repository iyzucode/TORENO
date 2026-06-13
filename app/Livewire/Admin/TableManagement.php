<?php

namespace App\Livewire\Admin;

use App\Models\Table;
use Illuminate\Support\Str;
use Livewire\Component;

class TableManagement extends Component
{
    public $table_number;
    public $table_id;
    public $isModalOpen = false;

    public function render()
    {
        return view('livewire.admin.table-management', [
            'tables' => Table::all()
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
        $this->table_number = '';
        $this->table_id = '';
    }

    public function store()
    {
        $uniqueRule = 'unique:tables,table_number';
        if (!empty($this->table_id)) {
            $uniqueRule .= ',' . $this->table_id;
        }

        $this->validate([
            'table_number' => 'required|' . $uniqueRule,
        ]);

        Table::updateOrCreate(['id' => $this->table_id ?: null], [
            'table_number' => $this->table_number,
            'qr_code_hash' => $this->table_id ? Table::find($this->table_id)->qr_code_hash : Str::random(10),
        ]);

        session()->flash('message', $this->table_id ? 'Meja Diperbarui.' : 'Meja Ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $table = Table::findOrFail($id);
        $this->table_id = $id;
        $this->table_number = $table->table_number;
    
        $this->openModal();
    }

    public function delete($id)
    {
        Table::find($id)->delete();
        session()->flash('message', 'Meja Dihapus.');
    }
}
