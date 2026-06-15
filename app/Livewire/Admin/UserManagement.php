<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $isTokenModalOpen = false;
    
    // Form fields
    public $user_id;
    public $name;
    public $email;
    public $role = 'cashier';
    public $password;
    
    // Token generation
    public $generatedToken = '';
    public $tokenUser = null;

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users
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
        $this->user_id = '';
        $this->name = '';
        $this->email = '';
        $this->role = 'cashier';
        $this->password = '';
        $this->resetValidation();
    }

    public function store()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($this->user_id ? ',' . $this->user_id : ''),
            'role' => 'required|in:owner,admin,cashier,kitchen',
        ];

        // Password is only required when creating a new user
        if (!$this->user_id) {
            $rules['password'] = 'required|string|min:8';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if (!$this->user_id && $this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->user_id ?: null], $data);

        session()->flash('message', $this->user_id ? 'User berhasil diperbarui.' : 'User berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
    
        $this->openModal();
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        
        // Protections
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            return;
        }
        
        if ($user->role === 'owner') {
            session()->flash('error', 'Anda tidak dapat menghapus akun Owner.');
            return;
        }

        $user->delete();
        session()->flash('message', 'User berhasil dihapus.');
    }

    public function generateToken($id)
    {
        $user = User::findOrFail($id);
        
        // Generate a random token
        $plainToken = Str::random(60);
        
        // Delete any existing tokens for this user's email to prevent duplicates
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        
        // Insert new token
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($plainToken),
            'created_at' => now()
        ]);
        
        $this->tokenUser = $user;
        $this->generatedToken = $plainToken;
        $this->isTokenModalOpen = true;
    }

    public function closeTokenModal()
    {
        $this->isTokenModalOpen = false;
        $this->generatedToken = '';
        $this->tokenUser = null;
    }
}
